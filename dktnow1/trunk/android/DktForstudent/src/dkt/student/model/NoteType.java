package dkt.student.model;

public class NoteType {
	private int columNoteTypeId;
	private String columNoteTypeName;
	private int columNotePic;


	public int getColumNotePic() {
		return columNotePic;
	}

	public void setColumNotePic(int columNotePic) {
		this.columNotePic = columNotePic;
	}

	public void setColumNoteTypeId(int columNoteTypeId) {
		this.columNoteTypeId = columNoteTypeId;
	}

	public int getColumNoteTypeId() {
		return columNoteTypeId;
	}

	public void setColumNoteTypeName(String columNoteTypeName) {
		this.columNoteTypeName = columNoteTypeName;
	}

	public String getColumNoteTypeName() {
		return columNoteTypeName;
	}
}
