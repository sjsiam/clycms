<?php

foreach ($categories as $category) {
    echo "<h2>" . htmlspecialchars($category['name']) . "</h2>";
}
