#!/bin/bash

QUIET=false
POSITIONAL=()
while [[ $# -gt 0 ]]; do
  case "$1" in
    --help)
      print_help
      exit 0
      ;;
    --version)
      print_version
      exit 0
      ;;
    -q|--quiet)
      QUIET=true
      shift
      ;;
    *)
      POSITIONAL+=("$1")
      shift
      ;;
  esac
done

set -- "${POSITIONAL[@]}"

GROUP="$1"
FILE="$2"

if [[ -z "$FILE" ]]; then
  echo "Select a CSV file:"
  select FILE in $(ls TimeTable_??_??_20??.csv 2>/dev/null | sort); do
    [[ -n "$FILE" ]] && break
  done
fi

[[ ! -f "$FILE" ]] && error_exit "File '$FILE' not found."

TEMP_FILE="/tmp/converted_$$.csv"
iconv -f WINDOWS-1251 -t UTF-8 "$FILE" -o "$TEMP_FILE" || error_exit "Failed to convert encoding."
sed -i 's/\r/\n/g' "$TEMP_FILE"

if [[ -z "$GROUP" ]]; then
  GROUPS=$(awk -F ';' 'NR>1{print $2}' "$TEMP_FILE" | sort | uniq)
  GROUP_COUNT=$(echo "$GROUPS" | wc -l)
  if [[ "$GROUP_COUNT" -eq 1 ]]; then
    GROUP="$GROUPS"
  else
    echo "Select academic group:"
    select GROUP in $GROUPS; do
      [[ -n "$GROUP" ]] && break
    done
  fi
fi

[[ -z "$GROUP" ]] && error_exit "Group not specified or not selected."

DATE_PART=$(echo "$FILE" | grep -oE '[0-9]{2}_[0-9]{2}_[0-9]{4}')
OUTPUT_FILE="Google_TimeTable_${DATE_PART}.csv"
$QUIET || echo "Subject,Start date,Start time,End date,End time,Description"
echo "Subject,Start date,Start time,End date,End time,Description" > "$OUTPUT_FILE"

lesson_num=1
awk -F ',' -v group="$GROUP" -v quiet="$QUIET" -v out="$OUTPUT_FILE" -v lesson_num_start=1 '
BEGIN {
  OFS=","; lesson_num = lesson_num_start;
}
NR > 1 {
  gsub(/^"|"$/, "", $1);

  if (index($1, group)) {
    for (i=1; i<=NF; i++) {
      gsub(/^"|"$/, "", $i);
    }

    subject_full = $1;
    date = $2;
    start = $3;
    end = $5;
    desc = $12;

    split(date, d, ".");
    newdate = d[2] "/" d[1] "/" d[3];

    cmd = "date -d \"" start "\" +\"%I:%M %p\""; cmd | getline start12; close(cmd);
    cmd = "date -d \"" end "\" +\"%I:%M %p\""; cmd | getline end12; close(cmd);

    final_subject = subject_full " №" lesson_num;
    line = final_subject OFS newdate OFS start12 OFS newdate OFS end12 OFS desc;

    if (quiet != "true") print line;
    print line >> out;
    lesson_num++;
  }
}' "$TEMP_FILE"

rm "$TEMP_FILE"
exit 0

