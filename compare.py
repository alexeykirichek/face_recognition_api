# import modules

import sys
from sys import argv
import face_recognition

# save input parameters

path_to_known_image=argv[1]
path_to_unknown_image=argv[2]
tolerance=float(argv[3])

# search faces

known_image = face_recognition.load_image_file(path_to_known_image)
unknown_image = face_recognition.load_image_file(path_to_unknown_image)

face_locations_known_image = face_recognition.face_locations(known_image)
face_locations_unknown_image = face_recognition.face_locations(unknown_image)

# check faces

count_faces_known_image = len(face_locations_known_image)
count_faces_unknown_image = len(face_locations_unknown_image)

if count_faces_known_image==0:
    print("Not found faces on first image")
    sys.exit()
elif count_faces_known_image>1:
    print(f'To many faces on first image ({count_faces_known_image})')
    sys.exit()

if count_faces_unknown_image==0:
    print("Not found faces on second image")
    sys.exit()
elif count_faces_unknown_image>1:
    print(f'To many faces on second image ({count_faces_unknown_image})')
    sys.exit()

# encode faces

known_encoding = face_recognition.face_encodings(known_image)[0]
unknown_encoding = face_recognition.face_encodings(unknown_image)[0]

# compare faces

results = face_recognition.compare_faces([known_encoding], unknown_encoding, tolerance)

# print results

print(results[0])
