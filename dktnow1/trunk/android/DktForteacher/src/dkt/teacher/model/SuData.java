package dkt.teacher.model;

public class SuData {
	
	// 视图层
	private int suId;
	private int suType;
	private String suPath;
	private String suUrl;
	private String suTitle;
	private int suX;
	private int suY;
	private int suWidth;
	private int suHeight;
	private String suTag;
	private byte[] suBitmap;
	private String suTran;
	private int haId;
	
	public void setSuId(int suId) {
		this.suId = suId;
	}
	public int getSuId() {
		return suId;
	}
	public void setSuType(int suType) {
		this.suType = suType;
	}
	public int getSuType() {
		return suType;
	}
	public void setSuPath(String suPath) {
		this.suPath = suPath;
	}
	public String getSuPath() {
		return suPath;
	}
	public void setSuUrl(String suUrl) {
		this.suUrl = suUrl;
	}
	public String getSuUrl() {
		return suUrl;
	}
	public void setSuTitle(String suTitle) {
		this.suTitle = suTitle;
	}
	public String getSuTitle() {
		return suTitle;
	}
	public void setSuX(int suX) {
		this.suX = suX;
	}
	public int getSuX() {
		return suX;
	}
	public void setSuY(int suY) {
		this.suY = suY;
	}
	public int getSuY() {
		return suY;
	}
	public void setSuWidth(int suWidth) {
		this.suWidth = suWidth;
	}
	public int getSuWidth() {
		return suWidth;
	}
	public void setSuHeight(int suHeight) {
		this.suHeight = suHeight;
	}
	public int getSuHeight() {
		return suHeight;
	}
	public void setSuTag(String suTag) {
		this.suTag = suTag;
	}
	public String getSuTag() {
		return suTag;
	}
	public void setSuBitmap(byte[] suBitmap) {
		this.suBitmap = suBitmap;
	}
	public byte[] getSuBitmap() {
		return suBitmap;
	}
	public void setSuTran(String suTran) {
		this.suTran = suTran;
	}
	public String getSuTran() {
		return suTran;
	}
	public void setHaId(int haId) {
		this.haId = haId;
	}
	public int getHaId() {
		return haId;
	}
}
