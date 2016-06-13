package dkt.student.socket;

import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.InetSocketAddress;
import java.net.Socket;

public class SocketConnection {

	/**
	 * 心跳包发送间隔时间
	 */
	private int heartSleepTime=10000;
	private InputStream in;
	private OutputStream out;
	private Socket socket;
	private IConnectionListener listener;
	private String authUser;
	private String authPwd;
	private HeartWorker heartWorker;
	private ReceiveWorker receiveWorker;
	private String host;
	private int port;
	/**
	 * 
	 * @param listener 监听器
	 * @param heartSleepTime 心跳包发送时间 一般间隔时间 10-15秒
	 */
	public SocketConnection(IConnectionListener listener,int heartSleepTime,
			String authUser,String authPwd, String host, int port){
		this.heartSleepTime=heartSleepTime;
		this.listener=listener;
		this.authPwd=authPwd;
		this.authUser=authUser;
		this.host = host;
		this.port = port;
	}
	/**
	 * 打开连接 如果连接存在 则先关闭
	 * @param host
	 * @param port
	 * @throws IOException
	 */
	public void connection() throws IOException{
		if(null!=socket){
			close();
		}
		socket = new Socket();
		InetSocketAddress remoteAddr = new InetSocketAddress(host, port);

		socket.connect(remoteAddr);
		//socket.setKeepAlive(true);

		// socket.setSoTimeout(MESConstants.TIME_OUT);
		//socket.setTcpNoDelay(true);
		in = socket.getInputStream();
		out = socket.getOutputStream();
		if(null!=listener){
			listener.onConnection(this);
		}
		if(this.heartSleepTime>0){
			if(null!=this.heartWorker){
				this.heartWorker.stopWorker();
			}
			this.heartWorker=new HeartWorker(this);
			this.heartWorker.startWorker();
		}
		if(null!=this.receiveWorker){
			this.receiveWorker.stopWorker();
		
		}
		this.receiveWorker=new ReceiveWorker(this);
		this.receiveWorker.startWorker();
	}
	
	/**
	 * 是否 连接
	 * @return
	 */
	public boolean isConnection(){
		return null!=this.socket&& this.socket
		.isConnected();
	}
	protected void sendData(byte[] headerData,byte[] body) throws IOException{
		synchronized(out){
		/*	*/
			out.write(headerData);
			out.write('\r');
			out.write('\n');
			if(null!=body){
				out.write(body);
			}
			out.flush();
		}
	}
	
	protected void sendData(byte[] data) throws IOException{
		synchronized(out){
			out.write(data);
			//out.flush();
		}
	}
	/**
	 * 关闭连接
	 */
	public void close(){
		
		if(null!=this.heartWorker){
			this.heartWorker.startWorker();
		}
		
		if (null != socket) {
			try {

				socket.shutdownInput();
				socket.shutdownOutput();
				socket.close();
			} catch (Exception e) {
				e.printStackTrace();
			} finally {
				socket = null;
			}
		}
		if (null != out) {
			try {
				out.close();
			} catch (Exception e) {

			} finally {
				out = null;
			}
		}
		if (null != in) {
			try {
				in.close();
			} catch (Exception e) {

			} finally {
				in = null;
			}
		}
		if(null!=this.listener){
			this.listener.onClose();
		}
		
	}
	public Socket getSocket() {
		return socket;
	}
	public String getAuthUser() {
		return authUser;
	}
	public String getAuthPwd() {
		return authPwd;
	}
	public int getHeartSleepTime() {
		return heartSleepTime;
	}
	public void setHeartSleepTime(int heartSleepTime) {
		this.heartSleepTime = heartSleepTime;
	}
	public IConnectionListener getListener() {
		return listener;
	}
	protected InputStream getInputStream() {
		return in;
	}
	
}
