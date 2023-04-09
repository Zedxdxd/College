package Olympiad;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.io.File;
import java.util.LinkedHashMap;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import javax.swing.JFileChooser;
import javax.swing.JOptionPane;

public class OlympiadController {

	private Olympiad olympiad;
	private OlympiadView view;
	
	private String athletesFileName = null;
	private String eventsFileName = null;
	private int yearToLoad = 0;
	
	public OlympiadController(OlympiadView view) {
		olympiad = Olympiad.getInstance();
		this.view = view;
		
		// adding action listeners to components in view
		view.addDrawingActionListener(new DrawingActionListener());
		view.addSetEventsActionListener(new SetEventsActionListener());
		view.addSetAthletesActionListener(new SetAthletesActionListener());
		view.addLoadOneYearActionListener(new LoadOneYearListener());
		view.addLoadDataActionListener(new LoadDataActionListener());
	}
	
	public void setAthletesFileName(String name) {
		athletesFileName = name;
	}
	
	public void setEventsFileName(String name) {
		eventsFileName = name;
	}
	
	// action listener for the draw button
	// gets the request from view and based on the request
	// gets the data from olympiad and forwards it to view
	public class DrawingActionListener implements ActionListener {

		@Override
		public void actionPerformed(ActionEvent e) {
			if (!olympiad.loadedData()) {
				JOptionPane.showMessageDialog(null, "Data isn't loaded.", "Warning",
						JOptionPane.WARNING_MESSAGE);
				return;
			}
			String req = view.getRequest();
			
			// user requested to draw a pie chart
			if (req.startsWith("Pie!")) {
				String[] filters = new String[6];
				
				// Pie!sportName!year!type(individual/team)!wonMedal
				Pattern filterPattern = Pattern.compile("Pie!(.*)!(.*)!(.*)!(.*)");
				Matcher filterMatcher = filterPattern.matcher(req);
				
				if (filterMatcher.matches()) {
					filters[0] = "-1";
					filters[1] = filterMatcher.group(1); // sportName
					filters[2] = filterMatcher.group(2); // year
					filters[3] = filterMatcher.group(3); // type (individual/team)
					filters[4] = filterMatcher.group(4); // wonMedal
					filters[5] = "-1"; // season
					
					// we dont set the filter for country, bc the olympiad method will go through every country and get number
					// maps country name to the number of competitors
					LinkedHashMap<String, Double> result = olympiad.numberCompetitorsByCountry(filters);
					view.setData(result);
				}
			}
			
			// user requested to draw a XY graph
			else if (req.startsWith("XY!")) {
				
				// XY!disciplines/height/weight!startYear!endYear
				Pattern dataPattern = Pattern.compile("XY!(.*)!(.*)!(.*)");
				Matcher dataMatcher = dataPattern.matcher(req);
				
				if (dataMatcher.matches()) {
					
					String type = dataMatcher.group(1);
					int startYear = Integer.parseInt(dataMatcher.group(2));
					int endYear = Integer.parseInt(dataMatcher.group(3));
					view.setStartEndYear(startYear, endYear);
					
					// maps yearSeason to the number/avg
					LinkedHashMap<String, Double> result = null;
					
					if (type.equals("disciplines")) {
						result = olympiad.numberDisciplinesByYearSeason(startYear, endYear);
					}
					else if (type.equals("height")) {
						result = olympiad.avgHeightByYearSeason(startYear, endYear);
					}
					else if (type.equals("weight")) {
						result = olympiad.avgWeightByYearSeason(startYear, endYear);
					}
					view.setData(result);
				}
			}
			view.repaint();
			
		}		
		
	}

	// action listener for the menu item to get the file
	// from which data about events is loaded
	public class SetEventsActionListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent e) {
			JFileChooser fileChooser = new JFileChooser();
			fileChooser.setCurrentDirectory(new File("."));
			
			int response = fileChooser.showOpenDialog(null);
			
			if (response == JFileChooser.APPROVE_OPTION) {
				eventsFileName = fileChooser.getSelectedFile().getAbsolutePath();
			}
			
		}
		
	}
	
	// action listener for the menu item to get the file
	// from which data about athletes is loaded
	public class SetAthletesActionListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent e) {
			JFileChooser fileChooser = new JFileChooser();
			fileChooser.setCurrentDirectory(new File("."));
			
			int response = fileChooser.showOpenDialog(null);
			
			if (response == JFileChooser.APPROVE_OPTION) {
				athletesFileName = fileChooser.getSelectedFile().getAbsolutePath();
			}
			
		}
		
	}
	
	// action listener for the menu item to select the 
	// individual mode for loading data (only load the data for that year)
	public class LoadOneYearListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent e) {
			String input = JOptionPane.showInputDialog("Enter a year:");
			
			// no input
			if (input == null) {
				JOptionPane.showMessageDialog(null, "Nothing was entered. Data about "
						+ "all years will be loaded.", "", JOptionPane.INFORMATION_MESSAGE);
				yearToLoad = 0;
			}
			else {
				try {
					yearToLoad = Integer.parseInt(input);
					// there is input and it's an integer
					JOptionPane.showMessageDialog(null, "Only the data about the year " + input
							+ " will be loaded.", "", JOptionPane.INFORMATION_MESSAGE);
				}
				catch (NumberFormatException exc) {
					// there is input but it isn't an integer
					JOptionPane.showMessageDialog(null, "Invalid year, please enter an integer. Data about "
							+ "all years will be loaded.", "", JOptionPane.WARNING_MESSAGE);
					yearToLoad = 0;
				}
			}
			
		}
		
	}

	// action listener for loading the data from set files
	public class LoadDataActionListener implements ActionListener{

		@Override
		public void actionPerformed(ActionEvent e) {
			if (athletesFileName == null && eventsFileName == null) {
				JOptionPane.showMessageDialog(null, "File for events nor athletes isn't set, "
						+ "so the data can't be loaded.", "Warning", JOptionPane.WARNING_MESSAGE);
			}
			else if (athletesFileName == null) {
				JOptionPane.showMessageDialog(null, "File for athletes isn't set "
						+ "so the data can't be loaded.", "Warning", JOptionPane.WARNING_MESSAGE);
			}
			else if (eventsFileName == null) {
				JOptionPane.showMessageDialog(null, "File for events isn't set, "
						+ "so the data can't be loaded.", "Warning", JOptionPane.WARNING_MESSAGE);
			}
			else {
				olympiad.parse(athletesFileName, eventsFileName, yearToLoad);
				JOptionPane.showMessageDialog(null, "Data successfully loaded.", 
						"", JOptionPane.PLAIN_MESSAGE);
			}
			
		}
		
	}
}
