import re


def version_cmp(version1, version2):
    def normalize(v):
        return [int(x) for x in re.sub(r'(\.0+)*$','', v).split(".")]
    return cmp(normalize(version1), normalize(version2))

def date_interval_to_seconds(interval):
    """
    Convert timedelta object into int representing the number of seconds.
    (Contains subseconds)
    """
    
    seconds = interval.days * 24 * 3600
    seconds = seconds + interval.seconds
    seconds = float(str(seconds) + "." + str(interval.microseconds))

    return seconds
