<?php
class CategoryContainers {
    private $con;   // Connection variable.
    private $username;    // Username variable.

    // Constructor to receive the connection and the username.
    // The connection and the username are passed as parameters.
    // The connection and the username are stored in the $con and $username variables.
    // The constructor is called when a new object is created using this class.
    public function __construct($con, $username) {
        $this->con = $con;  
        $this->username = $username;
    }

    // Function to show all the categories.
    // The function returns the html.
    // The function is called when the user is logged in.
    public function showAllCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");   // Prepare the query.
        $query->execute();      // Execute the query.

        $html = "<div class='previewCategories'>";  // Initialize the html.

        // Loop through the rows of the query.
        // Call the getCategoryHtml function.
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, true, true); 
        }

        return $html . "</div>";
    }

    public function showCategory($categoryId, $title = null) {
        $query = $this->con->prepare("SELECT * FROM categories WHERE id=:id");   // Prepare the query.
        $query->bindValue(":id", $categoryId);   // Bind the id.
        $query->execute();      // Execute the query.

        $html = "<div class='previewCategories noScroll'>";  // Initialize the html.

        // Loop through the rows of the query.
        // Call the getCategoryHtml function.
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, $title, true, true); 
        }

        return $html . "</div>";
    }

    private function getCategoryHtml($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if($tvShows && $movies) {
            $entities = EntityProvider::getEntities($this->con, $categoryId, 30);
        }
        else if($tvShows) {
            // Get tv show entities
        }
        else {
            // Get movie entities
        }

        if(sizeof($entities) == 0) {
            return;
        }

        $entitiesHtml = "";

        $previewProvider = new PreviewProvider($this->con, $this->username);
        
        foreach($entities as $entity) {
            $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }
}
?>