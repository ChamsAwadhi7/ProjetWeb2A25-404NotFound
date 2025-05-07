import cv2
import dlib
import face_recognition
import numpy as np
import sys
import json
import base64

def process_face(image_base64):
    # Décoder l'image
    img_data = base64.b64decode(image_base64)
    nparr = np.frombuffer(img_data, np.uint8)
    img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
    
    # Détection et encodage facial
    face_locations = face_recognition.face_locations(img)
    face_encodings = face_recognition.face_encodings(img, face_locations)
    
    if len(face_encodings) == 0:
        return None
    
    # Retourner le premier visage détecté
    return {
        'descriptor': face_encodings[0].tolist(),
        'landmarks': face_locations[0]
    }

def compare_faces(face1, face2):
    face1 = np.array(face1['descriptor'])
    face2 = np.array(face2['descriptor'])
    return float(face_recognition.face_distance([face1], face2)[0])

if __name__ == "__main__":
    if sys.argv[1] == 'process':
        result = process_face(sys.argv[2])
        print(json.dumps(result))
    elif sys.argv[1] == 'compare':
        face1 = json.loads(sys.argv[2])
        face2 = json.loads(sys.argv[3])
        similarity = compare_faces(face1, face2)
        print(json.dumps({'similarity': similarity}))