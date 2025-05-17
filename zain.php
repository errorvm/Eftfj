<?php
session_start();
require "config.php";

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <style>
        @keyframes rgbBorder {
            0% { border-color: black; }
            14% { border-color: black; }
            28% { border-color: black; }
            42% { border-color: black; }
            57% { border-color: black; }
            71% { border-color: black; }
            85% { border-color: black; }
            100% { border-color: black; }
        }

        body {
            background-color: #fe3e39;
            background-size: cover;
            font-family: 'Noto Sans', sans-serif;
            color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .container {
            width: 250px;
            height: 350px; /* Adjust height to auto to accommodate dynamic content */
            background-color: rgba(34, 34, 34, 0.1);
            border: 8px solid #0f0;
            padding: 10px;
            padding-bottom: 80px; /* Increased padding to ensure space for loading text */
            border-radius: 50px;
            box-shadow: 0 0 15px;
            transition: border-color 1.3s, box-shadow 1.3s;
            animation: rgbBorder 7s linear infinite;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .title, .timer, .period, .bet {
            font-weight: bold;
        }

        .title {
            font-size: 30px;
            text-align: center;
            margin-bottom: 20px;
            color: #fe3e39;
        }

        .timer {
            font-size: 50px;
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        .period {
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
            color: #fe3e39;
        }

        .bet {
            font-size: 30px;
            text-align: center;
            margin-bottom: 10px; /* Reduced margin to fit below the bet text */
            color: #fff;
        }

        .loading {
            font-size: 1.0rem;
            margin-top: 0px; /* Adjust to ensure it's directly below the bet text */
            color: #fff;
        }

        .button {
            display: block;
            padding: 0;
            margin: 20px auto;
            width: 150px;
            height: 50px;
            background-color: #fe3e39;
            border: none;
            color: #000;
            font-size: 20px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            line-height: 50px;
        }

        .telegram-button {
            display: block;
            margin: 0px;
            width: 150px;
            height: 50px;
            background-color: #0088cc;
            border: none;
            color: #fff;
            font-size: 20px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 2;
            display: none;
        }

        .popup {
            background-color: black;
            padding: 20px;
            color: greenyellow;
            border-radius: 10px;
            border: 3px solid white;
            text-align: center;
            width: 300px;
            height: 320px;
        }

        .popup .cancel-button {
            display: none;
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <canvas id="particleCanvas"></canvas>
    <div class="container">
        <div class="title">Team 2.0</div>
        <div id="timeRemaining" class="timer">00:03</div>
        <div id="issueNumber" class="period">Period: Loading</div>
        <div id="predictedNumber" class="bet">Bet on<br><br>Loading</div>
        <div id="loading" class="loading">Loading...</div>
        <button id="refreshButton" class="button">Refresh</button>
        <a href="https://t.me/TeamEarningZone" class="telegram-button">Telegram</a>
    </div>

    <div class="overlay" id="popupOverlay">
        <div class="popup">
            <h1>Warning!</h1>
            <h2>Click the 'Register' button to create a new account. If you use this mod with your old account, it will not work.</h2>
            <a id="registerButton" class="button" href="https://www.dmwin3.com/#/register?invitationCode=15256898639" target="_blank">Register</a>
            <button id="cancelButton" class="button cancel-button">Cancel</button>
        </div>
    </div>

    <!-- Link to the JavaScript file -->
    <script src="nn.js"></script>
    
    <script>
  const popupOverlay = document.getElementById("popupOverlay");
  const registerButton = document.getElementById("registerButton");
  const cancelButton = document.getElementById("cancelButton");

  // Function to hide the popup after 1 minute
  function hidePopup() {
    setTimeout(function() {
      popupOverlay.style.display = "none";
    }, 60000); // 1 minute in milliseconds
  }

  // Function to start the timer and show the cancel button
  function startTimer() {
    setTimeout(function() {
      cancelButton.style.display = "block";
    }, 60000); // 1 minute in milliseconds
  }

  // Function to handle cancel button click
  function handleCancel() {
    popupOverlay.style.display = "none";
  }

  // Automatically display the popup when the page loads
  window.onload = function() {
    if (!localStorage.getItem("registered")) {
      popupOverlay.style.display = "flex";
      registerButton.addEventListener("click", function() {
        localStorage.setItem("registered", "true");
        startTimer(); // Start the timer when "Register" is clicked
        hidePopup(); // Automatically hide popup after 1 minute
      });
      cancelButton.addEventListener("click", handleCancel);
    }
  };
</script>

    <script>
                document.addEventListener('DOMContentLoaded', function() {
            const predictedNumberElement = document.getElementById('predictedNumber');
            const timerElement = document.getElementById('timeRemaining');
            const issueNumberElement = document.getElementById('issueNumber');
            const refreshButton = document.getElementById('refreshButton');
            const loadingElement = document.getElementById('loading');

            let currentIssueNumber = null;
            let timerInterval = null;

            const fetchGameIssue = () => {
                const requestData = {
                    typeId: 1,
                    language: 0,
                    random: "e7fe6c090da2495ab8290dac551ef1ed",
                    signature: "1F390E2B2D8A55D693E57FD905AE73A7",
                    timestamp: 1723726679
                };

                return fetch('https://api.bdg88zf.com/api/webapi/GetGameIssue', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json;charset=UTF-8',
                        'Accept': 'application/json, text/plain, */*'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .catch(error => console.error('Error fetching game issue:', error));
            };

            const predictNextNumber = () => {
                return Math.random() < 0.5 ? 'Small' : 'Big';
            };

            const updatePrediction = (newIssueNumber) => {
                if (currentIssueNumber !== newIssueNumber) {
                    currentIssueNumber = newIssueNumber;
                    issueNumberElement.textContent = `Period: ${currentIssueNumber}`;

                    loadingElement.style.display = 'block';

                    setTimeout(() => {
                        const prediction = predictNextNumber();
                        predictedNumberElement.textContent = `Bet on\n\n${prediction}`;

                        loadingElement.style.display = 'none';

                        const lastPrediction = {
                            issueNumber: currentIssueNumber,
                            category: prediction
                        };
                        localStorage.setItem('lastPrediction', JSON.stringify(lastPrediction));
                    }, 2000);
                }
            };

            const updateTimer = () => {
                fetchGameIssue()
                .then(data => {
                    if (!data.data) {
                        console.error('No data received for game issue.');
                        return;
                    }

                    const { endTime, intervalM, issueNumber } = data.data;
                    if (!endTime || !intervalM) {
                        console.error('Incomplete data received for game issue.');
                        return;
                    }

                    const endDate = new Date(endTime);
                    const now = new Date();
                    const remainingTimeMs = endDate - now;

                    if (remainingTimeMs <= 0) {
                        timerElement.textContent = "00:00";
                        clearInterval(timerInterval);

                        setTimeout(() => {
                            fetchGameIssue().then(data => {
                                if (!data.data) {
                                    console.error('No data received for new game issue.');
                                    return;
                                }

                                const newIssueNumber = data.data.issueNumber;
                                updatePrediction(newIssueNumber); 
                            });
                            timerInterval = setInterval(updateTimer, 1000);
                        }, 3000);

                    } else {
                        const minutes = String(Math.floor((remainingTimeMs % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        const seconds = String(Math.floor((remainingTimeMs % (1000 * 60)) / 1000)).padStart(2, '0');
                        timerElement.textContent = `${minutes}:${seconds}`;
                    }

                    if (currentIssueNumber !== issueNumber) {
                        updatePrediction(issueNumber); 
                    }
                })
                .catch(error => console.error('Error fetching game issue:', error));
            };

            fetchGameIssue().then(data => {
                if (!data.data) {
                    console.error('No data received for initial game issue.');
                    return;
                }

                const initialIssueNumber = data.data.issueNumber;
                const storedPrediction = localStorage.getItem('lastPrediction');
                const lastPrediction = storedPrediction ? JSON.parse(storedPrediction) : {};

                if (lastPrediction.issueNumber === initialIssueNumber) {
                    predictedNumberElement.textContent = `Bet on\n\n${lastPrediction.category}`;
                } else {
                    updatePrediction(initialIssueNumber);
                }
            });

            timerInterval = setInterval(updateTimer, 1000);

            refreshButton.addEventListener('click', () => {
                fetchGameIssue().then(data => {
                    if (!data.data) {
                        console.error('No data received for new game issue.');
                        return;
                    }

                    const newIssueNumber = data.data.issueNumber;
                    updatePrediction(newIssueNumber);
                });
            });
        });
    </script>
</body>
    </html>
