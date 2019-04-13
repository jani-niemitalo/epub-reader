<?php
require_once("DB/mysqlConnection.php");

$queries = array();

array_push($queries, "
DROP TABLE `bookmarks`;
DROP TABLE `books`;
DROP TABLE `users`;
");


array_push($queries, "
CREATE TABLE `bookmarks` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `book_id` int(11) UNSIGNED NOT NULL,
  `location` varchar(64) NOT NULL,
  `ts` bigint UNSIGNED
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
array_push($queries, "
CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `isbn` varchar(17) NOT NULL,
  `path` text NOT NULL,
  `tn_path` varchar(32),
  `title` varchar(64) NOT NULL,
  `author` varchar(64) NOT NULL,
  `series` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");
array_push($queries, "
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");

array_push($queries, "
INSERT INTO `users` (`id`, `email`, `password`, `name`) VALUES
(123, 'jani.niemitalo@gmail.com', '123123', 'Seppo');
");
array_push($queries, "
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`user_id`,`book_id`),
  ADD KEY `book_bookmark` (`book_id`);
");

array_push($queries, "
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);
");

array_push($queries, "
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);
");
array_push($queries, "
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24075,
  MODIFY `author` varchar(64),
  MODIFY `series` varchar(64),
  MODIFY `title`  varchar(64) NOT NULL
");
array_push($queries, "
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;
");
array_push($queries, "
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `book_bookmark` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_bookmark` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

for ($i=0; $i < count($queries); $i++) {
    $res = $conn->query($queries[$i]);
    //echo $queries[$i]. "<br>";
    echo $res ? "[OK] " . $queries[$i] : "[ERR] " . $conn->error;
    echo "<br/>";
}
