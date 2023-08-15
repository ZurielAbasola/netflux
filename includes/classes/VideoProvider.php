<?php
class VideoProvider {
    public static function getUpNext ($con, $curentVideo) {
        $query = $con->prepare("SELECT * FROM videos
                                WHERE entityId = :entityId AND id != :videoId
                                AND (
                                    (season = :season AND episode > :episode) OR season > :season)
                                ORDER BY season, episode ASC LIMIT 1");
        $query->bindData(":entityId", $curentVideo->getEntityId());
        $query->bindData(":season", $curentVideo->getSeasonNumber());
        $query->bindData(":episode", $curentVideo->getEpisodeNumber());
        $query->bindData(":videoId", $curentVideo->getId());

        $query->execute();

        if ($query->rowCount() == 0) {
            $query = $con->prepare("SELECT * FROM videos
                                    WHERE season <= 1 AND episode <= 1
                                    AND id != :videoId
                                    ORDER BY views DESC LIMIT 1");
            $query->bindData(":videoId", $curentVideo->getId());
            $query->execute();
        }

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return new Video($con, $row);
    }
}
?>