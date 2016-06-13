package dkt.student.model;

public class Note {

	//笔记id
	private int noteId;
	//笔记名称
	private String noteName;
   

	//笔记类型 0默认 1自己创建 2别人的笔记
	private int noteType;
	//笔记创建时间
	private long noteCreateTime;
	private long noteUpdateTime;
	
	//笔记路径
	private String notePath;
	//笔记图片
	private int notePic;

	public int getNotePic() {
		return notePic;
	}

	public void setNotePic(int notePic) {
		this.notePic = notePic;
	}

	public String getNotePath() {
		return notePath;
	}

	public void setNotePath(String notePath) {
		this.notePath = notePath;
	}

	public int getNoteId() {
		return noteId;
	}

	public void setNoteId(int noteId) {
		this.noteId = noteId;
	}


	public int getNoteType() {
		return noteType;
	}

	public void setNoteType(int noteType) {
		this.noteType = noteType;
	}
	public long getNoteCreateTime() {
		return noteCreateTime;
	}

	public void setNoteCreateTime(long noteCreateTime) {
		this.noteCreateTime = noteCreateTime;
	}

	 public String getNoteName() {
			return noteName;
		}

		public void setNoteName(String noteName) {
			this.noteName = noteName;
		}

		public void setNoteUpdateTime(long noteUpdateTime) {
			this.noteUpdateTime = noteUpdateTime;
		}

		public long getNoteUpdateTime() {
			return noteUpdateTime;
		}


}
