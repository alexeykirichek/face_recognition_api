# import modules

from sys import argv
import face_recognition

# save input parameters

path_to_known_image=argv[1]

# search faces

known_image = face_recognition.load_image_file(path_to_known_image)
face_locations = face_recognition.face_locations(known_image)

# count faces

count_faces = len(face_locations)

# print results
if count_faces==0:
    print("False")
elif count_faces==1:
    print("True")
else:
    print(f'To many faces ({count_faces})')