from gensim.models import doc2vec
import os
import logging
import pymysql

db = pymysql.connect("localhost","","","")
cursor = db.cursor()

logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)


model=doc2vec.Doc2Vec.load(os.path.join(os.path.dirname(__file__),"model"))

cursor.execute("SELECT id FROM files WHERE tourism=1")
results = cursor.fetchall()
for row in results:
	sim_list=model.docvecs.most_similar(int(row[0]),topn=2)
	for sim in sim_list:
		cursor.execute("UPDATE files SET tourism=1 WHERE id="+str(sim[0]))

db.commit()
db.close()