import logging
import sys
from api_clients.api_client import AirtimeApiClient

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

def generate_liquidsoap_config(ss):
    data = ss['msg']
    fh = open('/etc/airtime/liquidsoap.cfg', 'w')
    fh.write("################################################\n")
    fh.write("# THIS FILE IS AUTO GENERATED. DO NOT CHANGE!! #\n")
    fh.write("################################################\n")

    for d in data:
        key = d['keyname']

        if 'channels' in key:
            continue
        elif 'bitrate' in key or 'stereo' in key:
            str_buffer = define_statement(d)
        else:
            str_buffer = variable_statement(d)

        fh.write(str_buffer.encode('utf-8'))

    fh.write('%define S1_STEREO true\n')
    fh.write('%define S2_STEREO true\n')
    fh.write('%define S3_STEREO true\n')

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
else:
    print "Unable to connect to the Airtime server."
    sys.exit(1)
