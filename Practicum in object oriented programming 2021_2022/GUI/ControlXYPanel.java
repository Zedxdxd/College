package Olympiad;

import java.awt.Color;
import java.awt.GridLayout;

import javax.swing.ButtonGroup;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JRadioButton;
import javax.swing.JTextField;
import javax.swing.SwingConstants;

public class ControlXYPanel extends JPanel {
	
	private JRadioButton radioDisciplines;
	private JRadioButton radioAvgHeight;
	private JRadioButton radioAvgWeight;
	
	private JLabel startYearLabel;
	private JLabel endYearLabel;
	
	private JTextField startYearText;
	private JTextField endYearText;
	
	private JButton startYearInfo;
	private JButton endYearInfo;
	
	private JPanel panelRadio;
	private JPanel panelYears;
	
	public ControlXYPanel() {
		radioDisciplines = new JRadioButton();
		radioDisciplines.setText("Show Disciplines");
		radioDisciplines.setSelected(true);
		radioDisciplines.setHorizontalAlignment(SwingConstants.CENTER);
		radioDisciplines.setFocusable(false);
		
		radioAvgHeight = new JRadioButton();
		radioAvgHeight.setText("Show Average Height");
		radioAvgHeight.setHorizontalAlignment(SwingConstants.CENTER);
		radioAvgHeight.setFocusable(false);
		
		radioAvgWeight = new JRadioButton();
		radioAvgWeight.setText("Show Average Weight");
		radioAvgWeight.setHorizontalAlignment(SwingConstants.CENTER);
		radioAvgWeight.setFocusable(false);
		
		ButtonGroup group = new ButtonGroup();
		group.add(radioDisciplines);
		group.add(radioAvgHeight);
		group.add(radioAvgWeight);
		
		panelRadio = new JPanel();
		panelRadio.setLayout(new GridLayout(1, 3));
		panelRadio.add(radioDisciplines);
		panelRadio.add(radioAvgHeight);
		panelRadio.add(radioAvgWeight);
		
		startYearLabel = new JLabel();
		startYearLabel.setText("Start year:");
		startYearLabel.setHorizontalAlignment(SwingConstants.CENTER);
		startYearText = new JTextField();
		
		endYearLabel = new JLabel();
		endYearLabel.setText("End year:");
		endYearLabel.setHorizontalAlignment(SwingConstants.CENTER);
		endYearText = new JTextField();
		
		startYearInfo = new JButton("Info");
		startYearInfo.setBackground(Color.cyan);
		startYearInfo.addActionListener(l -> {
			JOptionPane.showMessageDialog(null, "Enter the first year to show data for. The default "
					+ "is 1880.");
		});
		startYearInfo.setFocusable(false);
		
		endYearInfo = new JButton("Info");
		endYearInfo.setBackground(Color.cyan);
		endYearInfo.addActionListener(l -> {
			JOptionPane.showMessageDialog(null, "Enter the last year to show data for. The default "
					+ "is 2024.");
		});
		endYearInfo.setFocusable(false);
		
		panelYears = new JPanel();
		panelYears.setLayout(new GridLayout(2, 2));
		panelYears.add(startYearLabel);
		panelYears.add(startYearText);
		panelYears.add(startYearInfo);
		panelYears.add(endYearLabel);
		panelYears.add(endYearText);
		panelYears.add(endYearInfo);
		
		this.setLayout(new GridLayout(2, 1));
		this.add(panelRadio);
		this.add(panelYears);
		
		panelYears.setBackground(Color.cyan);
	}

	public String getFilteringData() {//XY!disciplines/height/weight!startYear!endYear
		StringBuilder sb = new StringBuilder();
		
		sb.append("XY!");
		
		if (radioDisciplines.isSelected()) {
			sb.append("disciplines!");
		}
		else if (radioAvgHeight.isSelected()) {
			sb.append("height!");
		}
		else if (radioAvgWeight.isSelected()) {
			sb.append("weight!");
		}
		
		String startYear = startYearText.getText();
		if (startYear.equals("")) {
			startYear = "1880";
		}
		else {
			try {
				Integer.parseInt(startYear);
			}
			catch (NumberFormatException e) {
				// entered number isn't an integer, so set the startYear to default
				startYear = "1880";
			}
		}
		sb.append(startYear + "!");
		
		String endYear = endYearText.getText();
		if (endYear.equals("")) {
			endYear = "2024";
		}
		else {
			try {
				Integer.parseInt(endYear);
			}
			catch (NumberFormatException e) {
				// entered number isn't an integer, so set the endYear to default
				endYear = "2024";
			}
		}
		sb.append(endYear);
		
		return sb.toString();
	}

}
