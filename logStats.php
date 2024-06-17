<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="Styles/styles.css">
        <title>Log Stats</title>
    </head>
    <body>
        <nav class="navbar">
            <div class="navbar-logo">Golf Stats Tracker</div>
            <ul class="navbar-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="logStats.php">Log Stats</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            </ul>
        </nav>
    
        <div class="banner-container">
            <div class="banner-overlay">
            </div>
        </div>

        <div class="log-stats-form">
            <form action="PHP/stats.php" method="post">
                <label for="course-name">Course Name:</label>
                <input type="text" id="course-name" name="course-name" required><br>
                <label for="date-played">Date Played:</label>
                <input type="date" id="date-played" name="date-played" required><br>
                <table>
                    <tr>
                        <th>Hole</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>Out</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>In</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>Par</td>
                        <td><label for="par1"></label>
                            <input type="number" id="par1" name="par1" required></td>
                        <td><label for="par2"></label>
                            <input type="number" id="par2" name="par2" required></td>
                        <td><label for="par3"></label>
                            <input type="number" id="par3" name="par3" required></td>
                        <td><label for="par4"></label>
                            <input type="number" id="par4" name="par4" required></td>
                        <td><label for="par5"></label>
                            <input type="number" id="par5" name="par5" required></td>
                        <td><label for="par6"></label>
                            <input type="number" id="par6" name="par6" required></td>
                        <td><label for="par7"></label>
                            <input type="number" id="par7" name="par7" required></td>
                        <td><label for="par8"></label>
                            <input type="number" id="par8" name="par8" required></td>
                        <td><label for="par9"></label>
                            <input type="number" id="par9" name="par9" required></td>
                        <td><label for="par-out"></label>
                            <input type="number" id="par-out" name="par-out" required></td>
                        <td><label for="par10"></label>
                            <input type="number" id="par10" name="par10" required></td>
                        <td><label for="par11"></label>
                            <input type="number" id="par11" name="par11" required></td>
                        <td><label for="par12"></label>
                            <input type="number" id="par12" name="par12" required></td>
                        <td><label for="par13"></label>
                            <input type="number" id="par13" name="par13" required></td>
                        <td><label for="par14"></label>
                            <input type="number" id="par14" name="par14" required></td>
                        <td><label for="par15"></label>
                            <input type="number" id="par15" name="par15" required></td>
                        <td><label for="par16"></label>
                            <input type="number" id="par16" name="par16" required></td>
                        <td><label for="par17"></label>
                            <input type="number" id="par17" name="par17" required></td>
                        <td><label for="par18"></label>
                            <input type="number" id="par18" name="par18" required></td>
                        <td><label for="par-in"></label>
                            <input type="number" id="par-in" name="par-in" required></td>
                        <td><label for="par-total"></label>
                            <input type="number" id="par-total" name="par-total" required></td>
                    </tr>
                    <tr>
                        <td>Yardage</td>
                        <td><input type="number" id="yard1" name="yard1" required></td>
                        <td><input type="number" id="yard2" name="yard2" required></td>
                        <td><input type="number" id="yard3" name="yard3" required></td>
                        <td><input type="number" id="yard4" name="yard4" required></td>
                        <td><input type="number" id="yard5" name="yard5" required></td>
                        <td><input type="number" id="yard6" name="yard6" required></td>
                        <td><input type="number" id="yard7" name="yard7" required></td>
                        <td><input type="number" id="yard8" name="yard8" required></td>
                        <td><input type="number" id="yard9" name="yard9" required></td>
                        <td><input type="number" id="yard-out" name="yard-out" required></td>
                        <td><input type="number" id="yard10" name="yard10" required></td>
                        <td><input type="number" id="yard11" name="yard11" required></td>
                        <td><input type="number" id="yard12" name="yard12" required></td>
                        <td><input type="number" id="yard13" name="yard13" required></td>
                        <td><input type="number" id="yard14" name="yard14" required></td>
                        <td><input type="number" id="yard15" name="yard15" required></td>
                        <td><input type="number" id="yard16" name="yard16" required></td>
                        <td><input type="number" id="yard17" name="yard17" required></td>
                        <td><input type="number" id="yard18" name="yard18" required></td>
                        <td><input type="number" id="yard-in" name="yard-in" required></td>
                        <td><input type="number" id="yard-total" name="yard-total" required></td>
                    </tr>
                    <tr>
                        <td>Score</td>
                        <td><input type="number" id="score1" name="score1" required></td>
                        <td><input type="number" id="score2" name="score2" required></td>
                        <td><input type="number" id="score3" name="score3" required></td>
                        <td><input type="number" id="score4" name="score4" required></td>
                        <td><input type="number" id="score5" name="score5" required></td>
                        <td><input type="number" id="score6" name="score6" required></td>
                        <td><input type="number" id="score7" name="score7" required></td>
                        <td><input type="number" id="score8" name="score8" required></td>
                        <td><input type="number" id="score9" name="score9" required></td>
                        <td><input type="number" id="score-out" name="score-out" required></td>
                        <td><input type="number" id="score10" name="score10" required></td>
                        <td><input type="number" id="score11" name="score11" required></td>
                        <td><input type="number" id="score12" name="score12" required></td>
                        <td><input type="number" id="score13" name="score13" required></td>
                        <td><input type="number" id="score14" name="score14" required></td>
                        <td><input type="number" id="score15" name="score15" required></td>
                        <td><input type="number" id="score16" name="score16" required></td>
                        <td><input type="number" id="score17" name="score17" required></td>
                        <td><input type="number" id="score18" name="score18" required></td>
                        <td><input type="number" id="score-in" name="score-in" required></td>
                        <td><input type="number" id="score-total" name="score-total" required></td>
                    </tr>
                    <tr>
                        <td>Fairway</td>
                        <td><select name="fw1" id="fw1"><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw2" name="fw2" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw3" name="fw3" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw4" name="fw4" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw5" name="fw5" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw6" name="fw6" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw7" name="fw7" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw8" name="fw8" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw9" name="fw9" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><input type="checkbox" id="fw-out" name="fw-out" ></td>
                        <td><select id="fw10" name="fw10" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw11" name="fw11" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw12" name="fw12" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw13" name="fw13" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw14" name="fw14" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw15" name="fw15" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw16" name="fw16" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw17" name="fw17" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><select id="fw18" name="fw18" ><option value="n/a">N/A</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                            <option value="straight">Straight</option></select></td>
                        <td><input type="checkbox" id="fw-in" name="fw-in" ></td>
                        <td><input type="checkbox" id="fw-total" name="fw-total" ></td>
                    </tr>
                    <tr>
                        <td>GIR</td>
                        <td><input type="checkbox" id="gir1" name="gir1" ></td>
                        <td><input type="checkbox" id="gir2" name="gir2" ></td>
                        <td><input type="checkbox" id="gir3" name="gir3" ></td>
                        <td><input type="checkbox" id="gir4" name="gir4" ></td>
                        <td><input type="checkbox" id="gir5" name="gir5" ></td>
                        <td><input type="checkbox" id="gir6" name="gir6" ></td>
                        <td><input type="checkbox" id="gir7" name="gir7" ></td>
                        <td><input type="checkbox" id="gir8" name="gir8" ></td>
                        <td><input type="checkbox" id="gir9" name="gir9" ></td>
                        <td><input type="checkbox" id="gir-out" name="gir-out" ></td>
                        <td><input type="checkbox" id="gir10" name="gir10" ></td>
                        <td><input type="checkbox" id="gir11" name="gir11" ></td>
                        <td><input type="checkbox" id="gir12" name="gir12" ></td>
                        <td><input type="checkbox" id="gir13" name="gir13" ></td>
                        <td><input type="checkbox" id="gir14" name="gir14" ></td>
                        <td><input type="checkbox" id="gir15" name="gir15" ></td>
                        <td><input type="checkbox" id="gir16" name="gir16" ></td>
                        <td><input type="checkbox" id="gir17" name="gir17" ></td>
                        <td><input type="checkbox" id="gir18" name="gir18" ></td>
                        <td><input type="checkbox" id="gir-in" name="gir-in" ></td>
                        <td><input type="checkbox" id="gir-total" name="gir-total" ></td>
                    </tr>
                    <tr>
                        <td>Putts</td>
                        <td><input type="number" id="putt1" name="putt1" required></td>
                        <td><input type="number" id="putt2" name="putt2" required></td>
                        <td><input type="number" id="putt3" name="putt3" required></td>
                        <td><input type="number" id="putt4" name="putt4" required></td>
                        <td><input type="number" id="putt5" name="putt5" required></td>
                        <td><input type="number" id="putt6" name="putt6" required></td>
                        <td><input type="number" id="putt7" name="putt7" required></td>
                        <td><input type="number" id="putt8" name="putt8" required></td>
                        <td><input type="number" id="putt9" name="putt9" required></td>
                        <td><input type="number" id="putt-out" name="putt-out" required></td>
                        <td><input type="number" id="putt10" name="putt10" required></td>
                        <td><input type="number" id="putt11" name="putt11" required></td>
                        <td><input type="number" id="putt12" name="putt12" required></td>
                        <td><input type="number" id="putt13" name="putt13" required></td>
                        <td><input type="number" id="putt14" name="putt14" required></td>
                        <td><input type="number" id="putt15" name="putt15" required></td>
                        <td><input type="number" id="putt16" name="putt16" required></td>
                        <td><input type="number" id="putt17" name="putt17" required></td>
                        <td><input type="number" id="putt18" name="putt18" required></td>
                        <td><input type="number" id="putt-in" name="putt-in" required></td>
                        <td><input type="number" id="putt-total" name="putt-total" required></td>
                    </tr>
                </table>
                <button type="submit">Submit</button>
            </form>
        </div>
    </body>
</html>