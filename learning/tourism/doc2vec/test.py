from gensim.models import doc2vec
import os
import logging
import pymysql

db = pymysql.connect("localhost","","","")
cursor = db.cursor()

logging.basicConfig(format='%(asctime)s : %(levelname)s : %(message)s', level=logging.INFO)


model=doc2vec.Doc2Vec.load(os.path.join(os.path.dirname(__file__),"model"))

record = open(os.path.join(os.path.dirname(__file__),"..","record.txt"),'r', encoding='utf-8')
record_lines=record.readlines()

sql_list=[]

for i in  range(0,52339):
	sql='INSERT INTO `files`(`id`,`name`, `vec1`, `vec2`, `vec3`, `vec4`, `vec5`, `vec6`, `vec7`, `vec8`, `vec9`, `vec10`, `vec11`, `vec12`, `vec13`, `vec14`, `vec15`, `vec16`, `vec17`, `vec18`, `vec19`, `vec20`, `vec21`, `vec22`, `vec23`, `vec24`, `vec25`, `vec26`, `vec27`, `vec28`, `vec29`, `vec30`, `vec31`, `vec32`, `vec33`, `vec34`, `vec35`, `vec36`, `vec37`, `vec38`, `vec39`, `vec40`, `vec41`, `vec42`, `vec43`, `vec44`, `vec45`, `vec46`, `vec47`, `vec48`, `vec49`, `vec50`, `vec51`, `vec52`, `vec53`, `vec54`, `vec55`, `vec56`, `vec57`, `vec58`, `vec59`, `vec60`, `vec61`, `vec62`, `vec63`, `vec64`) VALUES'

	sql+='('
	sql+= str(i)+","
	sql+= "'"+record_lines[i].replace('\n', '')+"',"
	sql+= ','.join(map(str, model.docvecs[i].tolist()))
	sql+= ')'
	cursor.execute(sql)

db.commit()
db.close()