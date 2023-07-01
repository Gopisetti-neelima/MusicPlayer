import os,sys
from io import BytesIO
from mutagen.mp3 import MP3
from mutagen.id3 import ID3
from PIL import Image
import json

song_path = os.path.join(sys.argv[1])
track = MP3(song_path)
tags = ID3(song_path)
song_details = {}

#print(tags)
song_details['artistName'] = tags['TPE1'][0]
song_details['songName'] = tags['TIT2'][0]
song_details['albumName'] = tags['TALB'][0]
song_details['songTrackId'] = tags['TRCK'][0]

pict = tags.get("APIC:").data
im = Image.open(BytesIO(pict))
im.save(sys.argv[2])
l=[]
for i in song_details:
    l.append('"'+i+'":"'+song_details[i]+'"')
print("{"+','.join(l)+"}")
