package dkt.student.socket;

import java.io.ByteArrayOutputStream;
import java.io.IOException;

/**
 * 接收消息
 * @author xumin
 *
 */
 class ReceiveWorker extends Thread {

	 private SocketConnection conn;
	 private boolean running=false;
	 public ReceiveWorker(SocketConnection socketConnection) {
		this.conn=socketConnection;
	}
	public void startWorker(){
		 this.running=true;
		 this.start();
	 }
	 public void stopWorker(){
		 this.running=false;
		 
	 }
	 
	 public void run(){
		 int c;
		 ByteArrayOutputStream receiveOut=new ByteArrayOutputStream();
		 try{
			 while(running){
				 //\r\n表示头接收 完成 
				 c=conn.getInputStream().read();
				 receiveOut.write(c);
				 if(c=='\r'){
					 c=conn.getInputStream().read();
					 receiveOut.write(c);
					 if(c=='\n'){
						 //头接收完成
						 IHeader header=new Header(receiveOut.toByteArray());
						 receiveOut.reset();
						 
						 if(null!=conn.getListener()){
							 conn.getListener().messageReceived(header, receiveBody(header));
						 }
					 }
				 }
			 }
		 }catch(Exception e){
			 e.printStackTrace();
			 conn.close();
		 }finally{
			 running=false;
		 }
		
	 }
	 /**
	  * 接收内容
	 * @throws IOException 
	  */
	 byte[] receiveBody(IHeader header) throws IOException{
		int conentLength= header.getContentLength();
		
		if(conentLength>0){
			//缓冲区
			byte[] buff=new byte[1024];
			 ByteArrayOutputStream receiveOut=new ByteArrayOutputStream();
			// System.out.println(dataLen);
			//当前循环需要接收的
			int currBuff = 0;
			//剩余的
			int remainLength=conentLength;
			while (remainLength>0) {
				currBuff = remainLength < buff.length ? remainLength : buff.length;

				int readLenght = conn.getInputStream().read(buff, 0, currBuff);
				receiveOut.write(buff, 0, readLenght);
				remainLength = remainLength - readLenght;
			}
			return receiveOut.toByteArray();
		}
		return null;
	 }
}
