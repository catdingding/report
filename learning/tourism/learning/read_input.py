import numpy as np
np.random.seed(1337)

''' Read input files '''
train_data = np.genfromtxt(os.path.join(os.path.dirname(__file__),'files.csv'), delimiter=',',skip_header=0)

''' The first column to the 199th column is used as input features '''
X_train = train_data[:,4:68]
X_train = X_train.astype('float32')

''' The 200-th column is the answer '''
y_train = train_data[:,0]
y_train = y_train.astype('int')

''' Convert to one-hot encoding '''
from keras.utils import np_utils
Y_train = np_utils.to_categorical(y_train,3)

''' Shuffle training data '''
from sklearn.utils import shuffle
X_train,Y_train = shuffle(X_train,Y_train,random_state=100)


predict_data = np.genfromtxt(os.path.join(os.path.dirname(__file__),'files_all.csv'), delimiter=',',skip_header=0)

X_predict = predict_data[:,4:68]
X_predict = X_predict.astype('float32')

id_predict = predict_data[:,2]
id_predict= id_predict.astype('int').astype('str')