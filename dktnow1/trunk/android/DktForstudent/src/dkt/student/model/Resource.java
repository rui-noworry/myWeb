package dkt.student.model;

public class Resource {

	private int resourceId; // 资源id
	private String resourceName; // 资源名称
	private int resourceType; // 资源类型
	private String resourcePath; // 资源路径
	private long resourceCreatTime; // 资源创建时间
	private int netId;

	public void setResourceId(int resourceId) {
		this.resourceId = resourceId;
	}

	public int getResourceId() {
		return resourceId;
	}

	public void setResourceName(String resourName) {
		this.resourceName = resourName;
	}

	public String getResourceName() {
		return resourceName;
	}

	public void setResourcePath(String resourcePath) {
		this.resourcePath = resourcePath;
	}

	public String getResourcePath() {
		return resourcePath;
	}

	public void setResourceCreatTime(long resourceCreatTime) {
		this.resourceCreatTime = resourceCreatTime;
	}

	public long getResourceCreatTime() {
		return resourceCreatTime;
	}

	public void setResourceType(int resourceType) {
		this.resourceType = resourceType;
	}

	public int getResourceType() {
		return resourceType;
	}

	public void setNetId(int netId) {
		this.netId = netId;
	}

	public int getNetId() {
		return netId;
	}
}
