package Olympiad;

public class Test {

	public static void main(String[] args) {
		Olympiad o = Olympiad.getInstance();
		OlympiadView view = new OlympiadView();
		OlympiadController controller = new OlympiadController(view);
	}

}
