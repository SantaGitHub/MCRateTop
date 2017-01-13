CREATE TABLE `MCRateTop` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `amount` float NOT NULL,
  `all_amount` float NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `source` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `MCRateTop`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `MCRateTop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
