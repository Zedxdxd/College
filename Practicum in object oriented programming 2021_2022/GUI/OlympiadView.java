package Olympiad;

import java.awt.BorderLayout;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.LinkedHashMap;

import javax.swing.ButtonGroup;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JRadioButton;

public class OlympiadView extends JFrame {

	private GraphPanel graphPanel; // Graph is drawn here
	private String graphType; // "Pie" or "XY"
	
	private JPanel graphButtons; // has two radio buttons (Pie and XY) and a draw button
	private JButton draw;
	private JRadioButton pieRadio, XYRadio;
	
	// holds the fields for user to input what he wants to draw
	private ControlPiePanel controlPiePanel;
	private ControlXYPanel controlXYPanel;
	
	// menu components
	private JMenuBar MenuBar;
	private JMenu fileMenu;
	private JMenuItem setEventsFile, setAthletesFile, loadOneYear, loadData,exit;
	
	// adds panels to the frame
	private void addComponents() {
		graphButtons = new JPanel();
		draw = new JButton("Draw!");
		draw.setFocusable(false);
		pieRadio = new JRadioButton("Pie Chart");
		pieRadio.setFocusable(false);
		XYRadio = new JRadioButton("XY Chart");
		XYRadio.setFocusable(false);
		ButtonGroup group = new ButtonGroup();
		group.add(pieRadio);
		group.add(XYRadio);
		graphButtons.add(pieRadio);
		graphButtons.add(XYRadio);
		graphButtons.add(draw);
		
		pieRadio.addActionListener(a -> {
			if (!graphType.equals("Pie")) {
				this.setGraphType("Pie");
			}
		});
		
		XYRadio.addActionListener(a -> {
			if (!graphType.equals("XY")){
				this.setGraphType("XY");
			}
		});
		
		pieRadio.setSelected(true);
		graphType = "Pie";
		
		graphPanel = new GraphPanel();
		//graphPanel.setSize(420, 400);
		this.add(graphPanel, BorderLayout.CENTER);
		this.add(graphButtons, BorderLayout.NORTH);
		
		controlPiePanel = new ControlPiePanel();
		this.add(controlPiePanel, BorderLayout.SOUTH);
	}
	
	// adds the menu to the frame
	private void addMenu() {
		MenuBar = new JMenuBar();
		
		fileMenu = new JMenu("File");
		MenuBar.add(fileMenu);
		
		setEventsFile = new JMenuItem("Set events file");
		fileMenu.add(setEventsFile);
		
		setAthletesFile = new JMenuItem("Set athletes file");
		fileMenu.add(setAthletesFile);
		
		loadOneYear = new JMenuItem("Load data for one year");
		fileMenu.add(loadOneYear);
		
		loadData = new JMenuItem("Load");
		fileMenu.add(loadData);
		
		exit = new JMenuItem("Exit");
		fileMenu.add(exit);
		exit.addActionListener(l -> {
			int response = JOptionPane.showConfirmDialog(null, "Do you want to exit?", "",
					JOptionPane.YES_NO_CANCEL_OPTION);
			
			if (response == JOptionPane.YES_OPTION) {
				OlympiadView.this.dispose();
			}
			else {
				OlympiadView.this.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
			}
		});
		
		this.setJMenuBar(MenuBar);
	}
	
	public OlympiadView() {
		this.setSize(600, 600);
		this.setLayout(new BorderLayout());
		addComponents();
		addMenu();
		this.setVisible(true);
		this.setTitle("Olympiad");
		
		// has to have the windowListener because of the YES NO CANCEL dialog
		this.addWindowListener(new WindowAdapter() {
			
			@Override
			public void windowClosing(WindowEvent e) {
				int response = JOptionPane.showConfirmDialog(null, "Do you want to exit?", "",
						JOptionPane.YES_NO_CANCEL_OPTION);
				
				if (response == JOptionPane.YES_OPTION) {
					OlympiadView.this.dispose();
				}
				else {
					OlympiadView.this.setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
				}
			}
		});
	}
	
	// from the Control panels gets what the user wants to draw
	public String getRequest() {
		if (this.graphType.equals("Pie")) {
			return this.controlPiePanel.getFilteringData();
		}
		else if (graphType.equals("XY")) {
			return controlXYPanel.getFilteringData();
		}
		return null;
	}
	
	// forwards the calculated data to the graphPanel for drawing
	public void setData(LinkedHashMap<String, Double> map) {
		graphPanel.setData(map);
	}
	
	// adding listeners to the components
	public void addDrawingActionListener(ActionListener a) {
		draw.addActionListener(a);
	}
	
	public void addSetEventsActionListener(ActionListener a) {
		setEventsFile.addActionListener(a);
	}
	
	public void addSetAthletesActionListener(ActionListener a) {
		setAthletesFile.addActionListener(a);
	}
	
	public void addLoadDataActionListener(ActionListener a) {
		loadData.addActionListener(a);
	}
	
	public void addLoadOneYearActionListener(ActionListener a) {
		loadOneYear.addActionListener(a);
	}
	
	// the user requested to draw a different Graph, so switching the controlPanel
	public void setGraphType(String str) {
		this.remove(graphPanel);
		graphPanel = new GraphPanel();
		graphPanel.setSize(420, 400);
		this.add(graphPanel, BorderLayout.CENTER);
		
		if (graphType.equals("Pie")) {
			this.remove(controlPiePanel);
		}
		else if (graphType.equals("XY")){
			this.remove(controlXYPanel);
		}
		
		graphType = str;
		if (graphType.equals("Pie")) {
			controlPiePanel = new ControlPiePanel();
			this.add(controlPiePanel, BorderLayout.SOUTH);
			graphPanel.setGraph(new PieChart());
		}
		else if (graphType.equals("XY")) {
			controlXYPanel = new ControlXYPanel();
			this.add(controlXYPanel, BorderLayout.SOUTH);
			graphPanel.setGraph(new XYGraph());
		}
		this.revalidate();
		
	}

	public void setStartEndYear(int startYear, int endYear) {
		if (graphType.equals("XY")) {
			graphPanel.setStartEndYear(startYear, endYear);
		}
	}
	
}
