<?php
class SeasonProvider {
    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function create($entity) {
        $query = $this->con->prepare("SELECT * FROM videos WHERE entityId=id
                                        AND isMovie=0
                                        ORDER BY season, episode ASC");     
    }
}
?>