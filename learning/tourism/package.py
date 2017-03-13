import os

output = open(os.path.join(os.path.dirname(__file__),"package.txt"),'w', encoding='utf-8')
record= open(os.path.join(os.path.dirname(__file__),"record.txt"),'w', encoding='utf-8')

for filename in os.listdir(os.path.join(os.path.dirname(__file__),"cut")):
	filesize=os.path.getsize(os.path.join(os.path.dirname(__file__),"cut",filename))
	if filesize<1024:
		continue

	try:
		this_novel=open(os.path.join(os.path.dirname(__file__),"cut",filename),'r',encoding='utf-8').read()
	except:
	    print(filename)
	else:
		record.write(filename+"\n")
		output.write(this_novel.replace('\n', '')+"\n")

output.close()
record.close()