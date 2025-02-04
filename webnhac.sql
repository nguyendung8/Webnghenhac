
TABLE `advertisement` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL
) 


TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) 


TABLE `favorite` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `songId` int(11) NOT NULL
) 


TABLE `feedback` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `songId` int(11) NOT NULL,
  `content` text NOT NULL,
  `rating` float DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5)
) 


TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL
) 


TABLE `song` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `isRestricted` tinyint(1) DEFAULT 0
) 


TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `status` int(11) NOT NULL DEFAULT 1
) 
