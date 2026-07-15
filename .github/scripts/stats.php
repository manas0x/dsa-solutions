<?php
/**
 * ==========================================================
 * LeetCode SVG Stats Card
 * Author : Manas Arora
 * Version: 2.0
 * ==========================================================
 *
 * Usage:
 * stats.php?u=manas0x
 * stats.php?u=manas0x&theme=dark
 * stats.php?u=manas0x&theme=light
 *
 */

declare(strict_types=1);

// ----------------------------------------------------------
// Headers & Error Reporting
// ----------------------------------------------------------
error_reporting(0);
// header('Content-Type: image/svg+xml; charset=UTF-8');
// header('Cache-Control: public, max-age=1800');
// header('Access-Control-Allow-Origin: *');

// ----------------------------------------------------------
// Configuration
// ----------------------------------------------------------
define('LEETCODE_API', 'https://leetcode.com/graphql');
define('CACHE_DIR', __DIR__ . '/cache/');
define('CACHE_TIME', 1800);

// ----------------------------------------------------------
// Themes
// ----------------------------------------------------------
$themes = [

    "dark" => [
        "bg" => "#0D1117",
        "card" => "#161B22",
        "border" => "#30363D",
        "title" => "#FFA116",
        "text" => "#C9D1D9",
        "muted" => "#8B949E",
        "easy" => "#00B8A3",
        "medium" => "#FFC01E",
        "hard" => "#FF375F",
        "accent" => "#58A6FF"
    ],

    "light" => [
        "bg" => "#FFFFFF",
        "card" => "#F6F8FA",
        "border" => "#D0D7DE",
        "title" => "#FFA116",
        "text" => "#24292F",
        "muted" => "#57606A",
        "easy" => "#00B8A3",
        "medium" => "#FFC01E",
        "hard" => "#FF375F",
        "accent" => "#0969DA"
    ],

    "github" => [
        "bg" => "#0D1117",
        "card" => "#161B22",
        "border" => "#30363D",
        "title" => "#58A6FF",
        "text" => "#FFFFFF",
        "muted" => "#8B949E",
        "easy" => "#3FB950",
        "medium" => "#D29922",
        "hard" => "#F85149",
        "accent" => "#58A6FF"
    ]

];

// ----------------------------------------------------------
// Parameters
// ----------------------------------------------------------
$username = 'manas0x';
$themeName = 'dark';

if (isset($_GET['u'])) $username = trim($_GET['u']);
if (isset($_GET['theme'])) $themeName = trim($_GET['theme']);

if (!isset($themes[$themeName])) {
    $themeName = "dark";
}

$theme = $themes[$themeName];

// ----------------------------------------------------------
// Cache
// ----------------------------------------------------------
if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777, true);
}

$cacheFile = CACHE_DIR . md5($username . $themeName) . ".json";

// ----------------------------------------------------------
// GraphQL Query
// ----------------------------------------------------------
$query = <<<GRAPHQL
query getUserProfile(\$username: String!) {

    matchedUser(username: \$username) {

        username

        profile {
            ranking
            reputation
            starRating
            userAvatar
            realName
        }

        submitStats: submitStatsGlobal {
            acSubmissionNum {
                difficulty
                count
                submissions
            }
            totalSubmissionNum {
                difficulty
                count
                submissions
            }
        }

        badges {
            id
            displayName
            icon
        }

        languageProblemCount {
            languageName
            problemsSolved
        }
    }

    userContestRanking(username: \$username) {
        attendedContestsCount
        rating
        globalRanking
        totalParticipants
        topPercentage
        badge {
            name
        }
    }
}
GRAPHQL;

// ----------------------------------------------------------
// Request Payload
// ----------------------------------------------------------
$payload = json_encode([
    "query" => $query,
    "variables" => [
        "username" => $username
    ]
]);

// ----------------------------------------------------------
// Read Cache
// ----------------------------------------------------------
$json = "";

if (
    file_exists($cacheFile) &&
    (time() - filemtime($cacheFile) < CACHE_TIME)
) {

    $json = file_get_contents($cacheFile);

} else {

    $headers = [
        "Content-Type: application/json",
        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36",
        "Referer: https://leetcode.com/"
    ];

    if (function_exists('curl_init')) {
        $ch = curl_init(LEETCODE_API);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $json = curl_exec($ch);
        curl_close($ch);
    } else {
        $options = [
            "http" => [
                "method" => "POST",
                "header" => implode("\r\n", $headers) . "\r\n",
                "content" => $payload,
                "timeout" => 15
            ]
        ];
        $context = stream_context_create($options);
        $json = @file_get_contents(LEETCODE_API, false, $context);
    }

    if ($json !== false) {
        file_put_contents($cacheFile, $json);
    } elseif (file_exists($cacheFile)) {
        $json = file_get_contents($cacheFile);
    }
}

// ----------------------------------------------------------
// Default Values
// ----------------------------------------------------------
$totalSolved = 0;
$easySolved = 0;
$mediumSolved = 0;
$hardSolved = 0;

$totalSubmissions = 0;

$ranking = 0;
$reputation = 0;
$starRating = 0;
$avatar = "";
$realName = "";

$contestRating = 0;
$contestRank = 0;
$topPercentage = 0;
$contestCount = 0;
$contestBadge = "";

$badgeCount = 0;

$acceptanceRate = 0;

// ----------------------------------------------------------
// Parse JSON
// ----------------------------------------------------------
if ($json !== false && !empty($json)) {

    $result = json_decode($json, true);

    if (
        isset($result["data"]["matchedUser"])
    ) {

        $user = $result["data"]["matchedUser"];

        // Profile
        if (isset($user["profile"])) {

            $ranking = $user["profile"]["ranking"] ?? 0;
            $reputation = $user["profile"]["reputation"] ?? 0;
            $starRating = $user["profile"]["starRating"] ?? 0;
            $avatarUrl = $user["profile"]["userAvatar"] ?? "";
            $realName = $user["profile"]["realName"] ?? "";

            if (!empty($avatarUrl)) {
                $imgData = false;
                if (function_exists('curl_init')) {
                    $ch = curl_init($avatarUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
                        "Referer: https://leetcode.com/"
                    ]);
                    $imgData = curl_exec($ch);
                    curl_close($ch);
                } else {
                    $ctx = stream_context_create(["http" => ["header" => "User-Agent: Mozilla/5.0\r\n"]]);
                    $imgData = @file_get_contents($avatarUrl, false, $ctx);
                }

                if ($imgData !== false && strlen($imgData) > 100 && !str_contains(strtolower(substr($imgData, 0, 50)), "<html")) {
                    $mime = "image/jpeg";
                    if (str_contains(strtolower($avatarUrl), ".png")) $mime = "image/png";
                    if (str_contains(strtolower($avatarUrl), ".gif")) $mime = "image/gif";
                    $avatar = "data:$mime;base64," . base64_encode($imgData);
                }
            }

        }

        // Solved Stats
        $totalAcSubmissions = 0;
        foreach (
            $user["submitStats"]["acSubmissionNum"]
            as $item
        ) {
                        switch ($item["difficulty"]) {

                case "All":
                    $totalSolved = $item["count"];
                    $totalAcSubmissions = $item["submissions"];
                    break;

                case "Easy":
                    $easySolved = $item["count"];
                    break;

                case "Medium":
                    $mediumSolved = $item["count"];
                    break;

                case "Hard":
                    $hardSolved = $item["count"];
                    break;
            }
        }

        // Total Submissions
        foreach (
            $user["submitStats"]["totalSubmissionNum"]
            as $item
        ) {

            if ($item["difficulty"] === "All") {

                $totalSubmissions = $item["submissions"];

                break;
            }
        }

        // Acceptance Rate
        if ($totalSubmissions > 0) {

            $acceptanceRate = round(
                ($totalAcSubmissions / $totalSubmissions) * 100,
                2
            );

        }

        // Badges
        if (isset($user["badges"])) {

            $badgeCount = count($user["badges"]);

        }

    }

    // Contest Data
    if (isset($result["data"]["userContestRanking"])) {

        $contest = $result["data"]["userContestRanking"];

        $contestRating = round(
            $contest["rating"] ?? 0
        );

        $contestRank =
            $contest["globalRanking"] ?? 0;

        $contestCount =
            $contest["attendedContestsCount"] ?? 0;

        $topPercentage =
            $contest["topPercentage"] ?? 0;

        if (
            isset($contest["badge"]) &&
            $contest["badge"] != null
        ) {

            $contestBadge =
                $contest["badge"]["name"];

        }

    }

}

// ----------------------------------------------------------
// Progress Bar Widths
// ----------------------------------------------------------
$barWidth = 340;

$easyWidth = 0;
$mediumWidth = 0;
$hardWidth = 0;

if ($totalSolved > 0) {

    $easyWidth =
        ($easySolved / $totalSolved) * $barWidth;

    $mediumWidth =
        ($mediumSolved / $totalSolved) * $barWidth;

    $hardWidth =
        ($hardSolved / $totalSolved) * $barWidth;

}

// ----------------------------------------------------------
// SVG Helpers
// ----------------------------------------------------------
function esc($text)
{
    return htmlspecialchars(
        (string)$text,
        ENT_QUOTES,
        "UTF-8"
    );
}

function value($v)
{
    if ($v === "" || $v === null || $v === "N/A") {
        return "0";
    }

    return esc($v);
}

// ----------------------------------------------------------
// Start SVG Output
// ----------------------------------------------------------
echo '<?xml version="1.0" encoding="UTF-8"?>';

?>
<svg
    width="420"
    height="300"
    viewBox="0 0 420 300"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
>
    <defs>

        <!-- Card Shadow -->
        <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
            <feDropShadow
                dx="0"
                dy="5"
                stdDeviation="8"
                flood-color="#000"
                flood-opacity="0.45"
            />
        </filter>

        <!-- Orange Glow -->
        <filter id="glow">
            <feGaussianBlur stdDeviation="3.5" result="coloredBlur"/>
            <feMerge>
                <feMergeNode in="coloredBlur"/>
                <feMergeNode in="SourceGraphic"/>
            </feMerge>
        </filter>

        <!-- Background Gradient -->
        <linearGradient id="cardGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="<?= $theme['card']; ?>"/>
            <stop offset="100%" stop-color="<?= $theme['bg']; ?>"/>
        </linearGradient>

        <!-- Orange Accent -->
        <linearGradient id="accentGradient" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stop-color="#FFA116"/>
            <stop offset="100%" stop-color="#FFCC66"/>
        </linearGradient>

        <!-- Animated Shine -->
        <linearGradient id="shine">
            <stop offset="-100%" stop-color="rgba(255,255,255,0)">
                <animate
                    attributeName="offset"
                    values="-1;2"
                    dur="3s"
                    repeatCount="indefinite"/>
            </stop>

            <stop offset="-50%" stop-color="rgba(255,255,255,.25)">
                <animate
                    attributeName="offset"
                    values="-0.5;2.5"
                    dur="3s"
                    repeatCount="indefinite"/>
            </stop>

            <stop offset="0%" stop-color="rgba(255,255,255,0)">
                <animate
                    attributeName="offset"
                    values="0;3"
                    dur="3s"
                    repeatCount="indefinite"/>
            </stop>
        </linearGradient>

        <clipPath id="avatarClip">
            <rect x="18" y="14" width="34" height="34" rx="17" />
        </clipPath>

    </defs>

    <!-- Background -->
    <rect
        x="5"
        y="5"
        width="410"
        height="290"
        rx="18"
        fill="url(#cardGradient)"
        stroke="<?= $theme['border']; ?>"
        stroke-width="2"
        filter="url(#shadow)"
    />

    <!-- LeetCode Logo / Avatar -->
    <?php if (!empty($avatar)): ?>
    <image
        x="18"
        y="14"
        width="34"
        height="34"
        href="<?= $avatar ?>"
        xlink:href="<?= $avatar ?>"
        clip-path="url(#avatarClip)"
        preserveAspectRatio="xMidYMid slice"
    />
    <?php else: ?>
    <text
        x="25"
        y="42"
        font-size="26"
    >🟨</text>
    <?php endif; ?>

    <!-- Title -->
    <text
        x="58"
        y="40"
        font-size="22"
        font-weight="700"
        fill="<?= $theme['title']; ?>"
        filter="url(#glow)"
        font-family="Segoe UI, Arial"
    >
        <?= esc($username); ?>
    </text>

    <!-- Subtitle -->
    <text
        x="58"
        y="60"
        font-size="12"
        fill="<?= $theme['muted']; ?>"
        font-family="Segoe UI, Arial"
    >
        LeetCode Statistics
    </text>

    <!-- Horizontal Divider -->
    <line
        x1="20"
        y1="75"
        x2="400"
        y2="75"
        stroke="<?= $theme['border']; ?>"
        stroke-width="1"
    />
        <!-- Summary Cards -->

    <!-- Total Solved -->
    <rect
        x="20"
        y="90"
        width="180"
        height="60"
        rx="10"
        fill="<?= $theme['card']; ?>"
        stroke="<?= $theme['border']; ?>"
    />

    <text
        x="35"
        y="115"
        font-size="13"
        fill="<?= $theme['muted']; ?>"
        font-family="Segoe UI, Arial"
    >
        Total Solved
    </text>

    <text
        x="35"
        y="140"
        font-size="24"
        font-weight="700"
        fill="<?= $theme['title']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= $totalSolved ?>
    </text>

    <!-- Acceptance -->
    <rect
        x="220"
        y="90"
        width="180"
        height="60"
        rx="10"
        fill="<?= $theme['card']; ?>"
        stroke="<?= $theme['border']; ?>"
    />

    <text
        x="235"
        y="115"
        font-size="13"
        fill="<?= $theme['muted']; ?>"
        font-family="Segoe UI, Arial"
    >
        Acceptance
    </text>

    <text
        x="235"
        y="140"
        font-size="24"
        font-weight="700"
        fill="<?= $theme['accent']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= number_format($acceptanceRate, 2) ?>%
    </text>

    <!-- Ranking -->
    <text
        x="25"
        y="175"
        font-size="14"
        fill="<?= $theme['text']; ?>"
        font-family="Segoe UI, Arial"
    >
        Ranking
    </text>

    <text
        x="140"
        y="175"
        font-size="14"
        font-weight="600"
        fill="<?= $theme['accent']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= value($ranking) ?>
    </text>

    <!-- Contest Rating -->
    <text
        x="25"
        y="198"
        font-size="14"
        fill="<?= $theme['text']; ?>"
        font-family="Segoe UI, Arial"
    >
        Contest Rating
    </text>

    <text
        x="140"
        y="198"
        font-size="14"
        font-weight="600"
        fill="<?= $theme['title']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= value($contestRating) ?>
    </text>

    <!-- Global Contest Rank -->
    <text
        x="25"
        y="221"
        font-size="14"
        fill="<?= $theme['text']; ?>"
        font-family="Segoe UI, Arial"
    >
        Global Rank
    </text>

    <text
        x="140"
        y="221"
        font-size="14"
        font-weight="600"
        fill="<?= $theme['accent']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= value($contestRank) ?>
    </text>

    <!-- Progress Bar Background -->
    <text
        x="25"
        y="248"
        font-size="13"
        fill="<?= $theme['muted']; ?>"
        font-family="Segoe UI, Arial"
    >
        Problems by Difficulty
    </text>

    <rect
        x="25"
        y="258"
        width="<?= $barWidth ?>"
        height="12"
        rx="6"
        fill="#2d333b"
    />

    <!-- Easy -->
    <rect
        x="25"
        y="258"
        width="0"
        height="12"
        rx="6"
        fill="<?= $theme['easy'] ?>"
    >
        <animate
            attributeName="width"
            from="0"
            to="<?= round($easyWidth,2) ?>"
            dur="0.8s"
            fill="freeze"/>
    </rect>

    <!-- Medium -->
    <rect
        x="<?= 25 + $easyWidth ?>"
        y="258"
        width="0"
        height="12"
        fill="<?= $theme['medium'] ?>"
    >
        <animate
            attributeName="width"
            from="0"
            to="<?= round($mediumWidth,2) ?>"
            dur="1.1s"
            fill="freeze"/>
    </rect>
        <!-- Hard -->
    <rect
        x="<?= 25 + $easyWidth + $mediumWidth ?>"
        y="258"
        width="0"
        height="12"
        rx="0"
        fill="<?= $theme['hard'] ?>"
    >
        <animate
            attributeName="width"
            from="0"
            to="<?= round($hardWidth,2) ?>"
            dur="1.4s"
            fill="freeze"/>
    </rect>

    <!-- Legend -->
    <circle cx="30" cy="285" r="4" fill="<?= $theme['easy'] ?>"/>
    <text
        x="40"
        y="289"
        font-size="12"
        fill="<?= $theme['text']; ?>"
        font-family="Segoe UI, Arial"
    >
        Easy <?= $easySolved ?>
    </text>

    <circle cx="125" cy="285" r="4" fill="<?= $theme['medium'] ?>"/>
    <text
        x="135"
        y="289"
        font-size="12"
        fill="<?= $theme['text']; ?>"
        font-family="Segoe UI, Arial"
    >
        Medium <?= $mediumSolved ?>
    </text>

    <circle cx="255" cy="285" r="4" fill="<?= $theme['hard'] ?>"/>
    <text
        x="265"
        y="289"
        font-size="12"
        fill="<?= $theme['text']; ?>"
        font-family="Segoe UI, Arial"
    >
        Hard <?= $hardSolved ?>
    </text>

    <!-- Right Information Panel -->
    <rect
        x="220"
        y="155"
        width="180"
        height="85"
        rx="10"
        fill="<?= $theme['card']; ?>"
        stroke="<?= $theme['border']; ?>"
    />

    <text
        x="235"
        y="178"
        font-size="13"
        fill="<?= $theme['muted']; ?>"
        font-family="Segoe UI, Arial"
    >
        Reputation
    </text>

    <text
        x="335"
        y="178"
        text-anchor="end"
        font-size="13"
        font-weight="600"
        fill="<?= $theme['accent']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= value($reputation) ?>
    </text>

    <text
        x="235"
        y="198"
        font-size="13"
        fill="<?= $theme['muted']; ?>"
        font-family="Segoe UI, Arial"
    >
        Star Rating
    </text>

    <text
        x="335"
        y="198"
        text-anchor="end"
        font-size="13"
        font-weight="600"
        fill="<?= $theme['title']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= value($starRating) ?>
    </text>

    <text
        x="235"
        y="218"
        font-size="13"
        fill="<?= $theme['muted']; ?>"
        font-family="Segoe UI, Arial"
    >
        Badges
    </text>

    <text
        x="335"
        y="218"
        text-anchor="end"
        font-size="13"
        font-weight="600"
        fill="<?= $theme['accent']; ?>"
        font-family="Segoe UI, Arial"
    >
        <?= $badgeCount ?>
    </text>

</svg>