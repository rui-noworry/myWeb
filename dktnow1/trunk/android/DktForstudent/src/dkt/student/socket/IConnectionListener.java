package dkt.student.socket;

public interface IConnectionListener {

	/**
	 * 连接创建触发
	 * @param conn
	 */
	public void onConnection(SocketConnection conn);
	/**
	 * 连接关闭触发
	 */
	public void onClose();
	/**
	 * 消息接收
	 * @param header
	 * @param body
	 */
	public void messageReceived(IHeader header,byte[] body);
}
