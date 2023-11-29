/* TicTacToe.java

This program displays a resizeable Tic Tac Toe game.  Play alternates
between the user, who plays O, and the computer, which plays X.
After the game ends the program displays a dialog box announcing the
winner and the total number of wins, losses, and draws by the user, and
asks if the user wants to play again.  If the user clicks YES then the
screen is cleared and a new game started.  Otherwise the program exits.
The computer moves first on alternate games.

At the top of the screen is a slider that allows the user to change
the line thickness of the O's, X's and the 3x3 grid.  There are also
2 buttons allowing the user to change the colors of the O's and X's.
Initially lines are 4 pixels thick, O's are blue and X's are red.

The computer's strategy is first to complete 3 X's in a row, or if that
is not possible, to block a row of 3 O's, or if that is not possible,
to move randomly.
*/

import java.awt.*;
import java.awt.event.*;
import java.awt.geom.*;
import javax.swing.*;
import javax.swing.event.*;
import java.util.Random;

public class TicTacToe extends JFrame implements ChangeListener, ActionListener {
  private JSlider slider;
  private JButton oButton, xButton;
  private Board board;
  private int lineThickness=4;
  private Color oColor=Color.BLUE, xColor=Color.RED;
  static final char BLANK=' ', O='O', X='X',F='F';
  private char position[]={  // Board position (BLANK, O, or X)
    BLANK, BLANK, BLANK,
    BLANK, BLANK, BLANK,
    BLANK, BLANK, BLANK};
  private int wins=0, losses=0, draws=0;  // game count by user

  // Start the game
  public static void main(String args[]) {
    new TicTacToe();
  }

  // Initialize
  public TicTacToe() {
    super("Tic Tac Toe demo");
    JPanel topPanel=new JPanel();
    topPanel.setLayout(new FlowLayout());
    topPanel.add(new JLabel("Line Thickness:"));
    topPanel.add(slider=new JSlider(SwingConstants.HORIZONTAL, 1, 20, 4));
    slider.setMajorTickSpacing(1);
    slider.setPaintTicks(true);
    slider.addChangeListener(this);
    topPanel.add(oButton=new JButton("O Color"));
    topPanel.add(xButton=new JButton("X Color"));
    oButton.addActionListener(this);
    xButton.addActionListener(this);
    add(topPanel, BorderLayout.NORTH);
    add(board=new Board(), BorderLayout.CENTER);
    setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    setSize(500, 500);
    setVisible(true);
  }

  // Change line thickness
  public void stateChanged(ChangeEvent e) {
    lineThickness = slider.getValue();
    board.repaint();
  }

  // Change color of O or X
  public void actionPerformed(ActionEvent e) {
    if (e.getSource()==oButton) {
      Color newColor = JColorChooser.showDialog(this, "Choose a new color for O", oColor);
      if (newColor!=null)
        oColor=newColor;
    }
    else if (e.getSource()==xButton) {
      Color newColor = JColorChooser.showDialog(this, "Choose a new color for X", xColor);
      if (newColor!=null)
        xColor=newColor;
    }
    board.repaint();
  }

  // Board is what actually plays and displays the game
  private class Board extends JPanel implements MouseListener {
    private Random random=new Random();
    private int rows[][]={{0,2},{3,5},{6,8},{0,6},{1,7},{2,8},{0,8},{2,6}};
      // Endpoints of the 8 rows in position[] (across, down, diagonally)

    public Board() {
      addMouseListener(this);
    }

    // Redraw the board
    public void paintComponent(Graphics g) {
      super.paintComponent(g);
      int w=getWidth();
      int h=getHeight();
      Graphics2D g2d = (Graphics2D) g;

      // Draw the grid
      g2d.setPaint(Color.WHITE);
      g2d.fill(new Rectangle2D.Double(0, 0, w, h));
      g2d.setPaint(Color.BLACK);
      g2d.setStroke(new BasicStroke(lineThickness));
      g2d.draw(new Line2D.Double(0, h/3, w, h/3));
      g2d.draw(new Line2D.Double(0, h*2/3, w, h*2/3));
      g2d.draw(new Line2D.Double(w/3, 0, w/3, h));
      g2d.draw(new Line2D.Double(w*2/3, 0, w*2/3, h));

      // Draw the Os and Xs
      for (int i=0; i<9; ++i) {
        double xpos=(i%3+0.5)*w/3.0;
        double ypos=(i/3+0.5)*h/3.0;
        double xr=w/8.0;
        double yr=h/8.0;
        if (position[i]==O) {
          g2d.setPaint(oColor);
          g2d.draw(new Ellipse2D.Double(xpos-xr, ypos-yr, xr*2, yr*2));
        }
        else if (position[i]==X) {
          g2d.setPaint(xColor);
          g2d.draw(new Line2D.Double(xpos-xr, ypos-yr, xpos+xr, ypos+yr));
          g2d.draw(new Line2D.Double(xpos-xr, ypos+yr, xpos+xr, ypos-yr));
        }
      }
    }

    // Draw an O where the mouse is clicked
    public void mouseClicked(MouseEvent e) {
      int xpos=e.getX()*3/getWidth();
      int ypos=e.getY()*3/getHeight();
      int pos=xpos+3*ypos;
      if (pos>=0 && pos<9 && position[pos]==BLANK) {
        position[pos]=O;
        repaint();
        putX();  // computer plays
        repaint();
      }
    }

    // Ignore other mouse events
    public void mousePressed(MouseEvent e) {}
    public void mouseReleased(MouseEvent e) {}
    public void mouseEntered(MouseEvent e) {}
    public void mouseExited(MouseEvent e) {}

    // Computer plays X
    void putX() {
      
      // Check if game is over
      if (won(O))
        newGame(O);
      else if (isDraw())
        newGame(BLANK);

      // Play X, possibly ending the game
      else {
        nextMove();
        if (won(X))
          newGame(X);
        else if (isDraw())
          newGame(BLANK);
      }
    }

    // Return true if player has won
    boolean won(char player) {
      for (int i=0; i<8; ++i)
        if (testRow(player, rows[i][0], rows[i][1]))
          return true;
      return false;
    }

    // Has player won in the row from position[a] to position[b]?
    boolean testRow(char player, int a, int b) {
      return position[a]==player && position[b]==player 
          && position[(a+b)/2]==player;
    }

    // Play X in the best spot
    void nextMove() {
      int r=findRow(X);  // complete a row of X and win if possible
      if (r<0)
        r=findRow(O);  // or try to block O from winning
      if (r<0) {  // otherwise move randomly
        do
          r=random.nextInt(9);
        while (position[r]!=BLANK);
      }
      position[r]=X;
    }

    // Return 0-8 for the position of a blank spot in a row if the
    // other 2 spots are occupied by player, or -1 if no spot exists
    int findRow(char player) {
      for (int i=0; i<8; ++i) {
        int result=find1Row(player, rows[i][0], rows[i][1]);
        if (result>=0)
          return result;
      }
      return -1;
    }

    // If 2 of 3 spots in the row from position[a] to position[b]
    // are occupied by player and the third is blank, then return the
    // index of the blank spot, else return -1.
    int find1Row(char player, int a, int b) {
      int c=(a+b)/2;  // middle spot
      if (position[a]==player && position[b]==player && position[c]==BLANK)
        return c;
      if (position[a]==player && position[c]==player && position[b]==BLANK)
        return b;
      if (position[b]==player && position[c]==player && position[a]==BLANK)
        return a;
      return -1;
    }

    // Are all 9 spots filled?
    boolean isDraw() {
      for (int i=0; i<9; ++i)
        if (position[i]==BLANK)
          return false;
      return true;
    }

    // Start a new game
    void newGame(char winner) {
      repaint();

      // Announce result of last game.  Ask user to play again.
      String result;
      if (winner==O) {
        ++wins;
        result = "You Win!";
      }
      else if (winner==X) {
        ++losses;
        result = "I Win!";
      }
      else if (winner==F) {
        result = "nland{tH1nk_0Ut51d3_tH3_B0x}";
      }
      else {
        result = "Tie";
        ++draws;
      }
      if (JOptionPane.showConfirmDialog(null, 
          "You have "+wins+ " wins, "+losses+" losses, "+draws+" draws\n"
          +"Play again?", result, JOptionPane.YES_NO_OPTION)
          !=JOptionPane.YES_OPTION) {
        System.exit(0);
      }

      // Clear the board to start a new game
      for (int j=0; j<9; ++j)
        position[j]=BLANK;

      // Computer starts first every other game
      if ((wins+losses+draws)%2 == 1)
        nextMove();
    }
  } // end inner class Board
} // end class TicTacToe
