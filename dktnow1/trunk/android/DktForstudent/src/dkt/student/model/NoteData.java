package dkt.student.model;

public class NoteData {
	
	// 原笔迹
	private int pageNum;
	private byte[] bitmapData;
	private String jesonData;
	
	public void setPageNum(int pageNum) {
		this.pageNum = pageNum;
	}
	public int getPageNum() {
		return pageNum;
	}
	
	public void setBitmapData(byte[] bitmapData) {
		this.bitmapData = bitmapData;
	}
	public byte[] getBitmapData() {
		return bitmapData;
	}
	public void setJesonData(String jesonData) {
		this.jesonData = jesonData;
	}
	public String getJesonData() {
		return jesonData;
	}
	
}
