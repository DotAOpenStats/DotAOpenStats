ALTER TABLE  bans ADD INDEX (  name );
ALTER TABLE  bans ADD INDEX (  date );

ALTER TABLE  games ADD INDEX (  datetime );
ALTER TABLE  games ADD INDEX (  `map` );
ALTER TABLE  games ADD INDEX (  gamestate );

ALTER TABLE  gameplayers ADD INDEX (  gameid );
ALTER TABLE  gameplayers ADD INDEX (  colour );
ALTER TABLE  gameplayers ADD INDEX (  name );

ALTER TABLE  dotagames ADD INDEX (  id );
ALTER TABLE  dotagames ADD INDEX (  gameid );
ALTER TABLE  dotagames ADD INDEX (  winner );

ALTER TABLE  dotaplayers ADD INDEX (  newcolour );
ALTER TABLE  dotaplayers ADD INDEX (  hero );
ALTER TABLE  dotaplayers ADD INDEX (  item1 );
ALTER TABLE  dotaplayers ADD INDEX (  item2 );
ALTER TABLE  dotaplayers ADD INDEX (  item3 );
ALTER TABLE  dotaplayers ADD INDEX (  item4 );
ALTER TABLE  dotaplayers ADD INDEX (  item5 );
ALTER TABLE  dotaplayers ADD INDEX (  item6 );

ALTER TABLE  scores ADD INDEX (  name );
ALTER TABLE  scores ADD INDEX (  score );