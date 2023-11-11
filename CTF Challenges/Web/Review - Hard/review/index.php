<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByteBites Bistro</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <h1>ByteBites Bistro</h1>
    </header>
    <main>
        <img src="uploads/food.jpg" alt="Review Banner" id="review-banner">
        <div class="review-form">
            <h2>Please Sent Us Your Review</h2>
            <form action="submit_review.php" method="POST" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="review">Review:</label>
                <textarea id="review" name="review" rows="4" required></textarea>
                <div class="rating">
                    <label>
              <input type="radio" name="stars" value="1" required />
              <span class="icon">★</span>
            </label>
                    <label>
              <input type="radio" name="stars" value="2" />
              <span class="icon">★★</span>
            </label>
                    <label>
              <input type="radio" name="stars" value="3" />
              <span class="icon">★★★</span>
            </label>
                    <label>
              <input type="radio" name="stars" value="4" />
              <span class="icon">★★★★</span>
            </label>
                    <label>
              <input type="radio" name="stars" value="5" />
              <span class="icon">★★★★★</span>
            </label>
                </div>
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <button type="submit">Submit Review</button>
            </form>
        </div>

		<?php
		$result = isset($_GET["result"]) ? $_GET["result"] : "";
		$status = isset($_GET["status"]) ? $_GET["status"] : "success";
		$resultClass = $status === "error" ? "error" : "success";

		if ($result) {
			echo "<div class='result $resultClass'>" .
				htmlspecialchars($result) . "</div>";
		}
		?>
        
    </main>
</body>

</html>
