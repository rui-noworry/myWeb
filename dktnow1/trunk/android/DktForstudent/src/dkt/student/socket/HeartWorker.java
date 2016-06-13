package dkt.student.socket;

import java.io.ByteArrayOutputStream;
import java.io.IOException;

/**
 * 心跳包
 * 
 * @author xumin
 * 
 */
class HeartWorker extends Thread {

	private boolean running = false;
	private SocketConnection conn;
	private int isF = 0;
	
	public HeartWorker(SocketConnection conn) {
		this.conn = conn;
	}

	/**
	 * 创建心跳包
	 * 
	 * @return
	 */
	byte[] createHeartData() {
		IHeader header = new Header();
		try {
			header.addHeader(IHeader.HEAD_AUTH_USER_KEY, conn.getAuthUser());
			header.addHeader(IHeader.HEAD_AUTH_PWD_KEY, conn.getAuthPwd());
			header.addHeader(IHeader.HEAD_AUTH_USER_TYPE, "1");
			header.addHeader(IHeader.HEAD_AUTH_USER_VERSION, "20130809");
			header.addHeader(IHeader.HEAD_CONTENT_LENGTH_KEY, "0");
			
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	
	

		return header.toString().getBytes();
	}

	public void run() {
		byte[] heartData = createHeartData();
		try {
			while (running) {
				
				System.out.println(new String(heartData)+"=========header111=============");

				if(conn.isConnection()) {
					conn.sendData(heartData,null);  
					System.out.println("====发送头信息============");
				}else{
					running=false;
					System.out.println("====无连接============");
					//conn.connection();		
				}  
				
				try {
					Thread.sleep(conn.getHeartSleepTime());
					
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
		} catch (Exception e) {
			e.printStackTrace();

		} finally {
			heartData = null;
			running=false;
			
		}

	}

	public void stopWorker() {
		this.running = false;
	}

	public void startWorker() {
		if (running) {
			return;
		}
		this.running = true;
		this.start();
	}
}
