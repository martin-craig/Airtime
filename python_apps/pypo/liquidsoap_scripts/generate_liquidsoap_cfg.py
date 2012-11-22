import logging
import sys
from api_clients.api_client import AirtimeApiClient

mp3_to_vorbis_bitrate = {
    "24": "-.1",
    "32": "-.1",
    "64": "",
    "96": ".2",
    "128": ".4",
    "160": ".5",
    "192": ".6",
    "224": ".7",
    "256": ".8",
    "320": ".9",
}

def define_statement(d):
    key = d['keyname'].upper()
    value = d['value']

    if len(value) == 0:
        value = '128'

    return "%%define %s %s\n" % (key, value)

def variable_statement(d):
    key = d['keyname']
    val = d[u'value']
    str_buffer = d[u'keyname'] + " = "
    if d['type'] == 'string':
        val = '"%s"' % val
    else:
        val = val if len(val) > 0 else "0"
    return "%s = %s\n" % (key, val)

def convert_to_vorbis_bitrate_format(bitrate):
    if bitrate in mp3_to_vorbis_bitrate:
        return mp3_to_vorbis_bitrate[bitrate]
    else:
        return mp3_to_vorbis_bitrate['192']

def map_bitrate(d, data, stream_type):
    if stream_type == "mp3":
        return d['value']
    else:
        return convert_to_vorbis_bitrate_format(d['value'])

def convert_to_dict(data):
    config = {}
    for d in data:
        config[d['keyname']] = d

    return config

def generate_liquidsoap_config(ss):
    data = ss['msg']
    fh = open('/etc/airtime/liquidsoap.cfg', 'w')
    fh.write("################################################\n")
    fh.write("# THIS FILE IS AUTO GENERATED. DO NOT CHANGE!! #\n")
    fh.write("################################################\n")

    data = convert_to_dict(data)
    for key in data:
        d = data[key]

        if 'bitrate' in key:
            stream_id = d['keyname'].split("_")[0]
            d['keyname'] = "%s_MP3_BITRATE" % stream_id
            d['value'] = map_bitrate(d, data, 'mp3')
            str_buffer = define_statement(d)
            d['keyname'] = "%s_VORBIS_QUALITY" % stream_id
            d['value'] = map_bitrate(d, data, 'ogg')
            str_buffer += define_statement(d)
        else:
            str_buffer = variable_statement(d)

        fh.write(str_buffer.encode('utf-8'))

    fh.write('log_file = "/var/log/airtime/pypo-liquidsoap/<script>.log"\n')
    fh.close()

logging.basicConfig(format='%(message)s')
ac = AirtimeApiClient(logging.getLogger())
ss = ac.get_stream_setting()

if ss is not None:
    try:
        generate_liquidsoap_config(ss)
    except Exception, e:
        logging.error(e)
        raise
else:
    print "Unable to connect to the Airtime server."
    sys.exit(1)
