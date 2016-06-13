package dkt.teacher.socket;
/**
 * 信息头
 * @author xumin
 *
 */
public interface IHeader {
	public static final String HEADER_ENCODING_CHARSET = "utf-8";
	/**
	 * 成功状态
	 */
	public static final int SC_OK = 200;
	/**
	 * 非法帐号
	 */
	public static final int SC_INVALID = 201;

	/**
	 * 同一帐号在不同地方请求
	 */
	public static final int SC_REPEAT_REQUEST = 202;
	/**
	 * 内部错误
	 */
	public static final int SC_ERROR = 505;

	/* 状态 */
	public static final String HEAD_STATUS_KEY = "status";
	public static final String HEAD_CONTENT_TYPE_KEY = "content-type";
	/**
	 * 请求或者发送数据长度
	 */
	public static final String HEAD_CONTENT_LENGTH_KEY = "content-length";
	/**
	 * 用户类型
	 * */
	public static final String HEAD_AUTH_USER_TYPE = "auth-type";
	/**
	 * 版本号
	 * */
	public static final String HEAD_AUTH_USER_VERSION = "auth-version";
	/**
	 * 验证帐号
	 */
	public static final String HEAD_AUTH_USER_KEY = "auth-user";
	/**
	 * 验证密码
	 */
	
	
	public static final String HEAD_AUTH_PWD_KEY = "auth-password";
	/**
	 * 推送帐号
	 * */
	public static final String HEAD_AUTH_PUSH_USERS = "receiver";
	
	public int getContentLength();
	public int getResponseCode();
	public void addHeader(String name,String value) throws Exception;
	public String getHeader(String key);
	public void destroy();
}
