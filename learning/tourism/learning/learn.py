import theano
import numpy as np
import os

exec(open(os.path.join(os.path.dirname(__file__),'read_input.py')).read())

from keras.models import Sequential
from keras.layers.core import Dense, Activation,Dropout
from keras.regularizers import l1,l2

model = Sequential()
model.add(Dense(64,input_dim=64, W_regularizer=l2(0.001)))
model.add(Activation('relu'))
model.add(Dropout(0.4))

model.add(Dense(256, W_regularizer=l2(0.001)))
model.add(Activation('relu'))
model.add(Dropout(0.4))

model.add(Dense(3, W_regularizer=l2(0.001)))
model.add(Activation('softmax'))

model.summary()

from keras.optimizers import SGD, Adam, RMSprop, Adagrad

adam=Adam(lr=0.00017)

model.compile(loss='categorical_crossentropy',
			  optimizer=adam,
			  metrics=['accuracy'])

batch_size = 16
nb_epoch = 350
shuffle=True

history = model.fit( X_train,
					 Y_train,
					 batch_size=batch_size,
					 nb_epoch=nb_epoch,
					 shuffle=shuffle,
					 validation_split=0.1,
					 verbose=1)

loss	= history.history.get('loss')
acc 	= history.history.get('acc')

val_loss = history.history.get('val_loss')
val_acc = history.history.get('val_acc')

'''
import pymysql

db = pymysql.connect("localhost","catdingt","jda730730","report")
cursor = db.cursor()


result=model.predict(X_predict)

for index in range(0,len(result)):
	cursor.execute("UPDATE files SET result="+str(result[index].argmax())+" WHERE id='"+id_predict[index]+"'")

db.commit()
db.close()
'''
import matplotlib.pyplot as plt

plt.figure(0)
plt.subplot(121)
plt.plot(range(len(loss)), loss,label='Training')
plt.plot(range(len(val_loss)), val_loss,label='Validation')
plt.title('Loss')
plt.legend(loc='upper left')
plt.subplot(122)
plt.plot(range(len(acc)), acc,label='Training')
plt.plot(range(len(val_acc)), val_acc,label='Validation')
plt.title('Accuracy')
plt.savefig(os.path.join(os.path.dirname(__file__),'00_firstModel.png'),dpi=300,format='png')
plt.close()