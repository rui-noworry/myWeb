package dkt.teacher.socket;

public class PushWorker extends Thread{
	private boolean running = false;
	private SocketConnection conn;
	private String receiver;
	private String body;
	
	public PushWorker(SocketConnection conn, String receiver, String body) {
		this.conn = conn;
		this.receiver = receiver;
		this.body = body;
		
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
			header.addHeader(IHeader.HEAD_AUTH_USER_TYPE, "2");
			header.addHeader(IHeader.HEAD_AUTH_USER_VERSION, "20130809");
			header.addHeader(IHeader.HEAD_CONTENT_LENGTH_KEY, ""+body.length());
			header.addHeader(IHeader.HEAD_AUTH_PUSH_USERS, receiver);//receiver
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	
		System.out.println("====header============"+header);
 
		return header.toString().getBytes();
	}

	public void run() {
		byte[] heartData = createHeartData();
		try {
			while (running) {
				if(conn.isConnection()) {
					conn.sendData(heartData,body.getBytes());
					System.out.println(body);
				}else{
					conn.connection();
					Thread.sleep(1000);
					conn.sendData(heartData,body.getBytes());
				}
				running=false;
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
		this.stop();
	}

	public void startWorker() {
		if (running) {
			return;
		}
		this.running = true;
		this.start();
	}
}
