<?php
class VideoProvider {
    public static function getUpNext($con, $curentVideo) {
        $query = $con->prepare("SELECT * FROM videos
                                WHERE entityId = :entityId AND id != :videoId
                                AND (
                                    (season = :season AND episode > :episode) OR season > :season)
                                ORDER BY season, episode ASC LIMIT 1");
        $query->bindValue(":entityId", $curentVideo->getEntityId());
        $query->bindValue(":season", $curentVideo->getSeasonNumber());
        $query->bindValue(":episode", $curentVideo->getEpisodeNumber());
        $query->bindValue(":videoId", $curentVideo->getId());

        $query->execute();

        if ($query->rowCount() == 0) {
            $query = $con->prepare("SELECT * FROM videos
                                    WHERE season <= 1 AND episode <= 1
                                    AND id != :videoId
                                    ORDER BY views DESC LIMIT 1");
            $query->bindValue(":videoId", $curentVideo->getId());
            $query->execute();
        }

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return new Video($con, $row);
    }
}
?>