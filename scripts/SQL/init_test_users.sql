create or replace function public.truncate_if_exists(_table text, _schema text default null)
  returns text
  language plpgsql as
$func$
declare
   _qual_tbl text := concat_ws('.', quote_ident(_schema), quote_ident(_table));
   _row_found bool;
begin
   if to_regclass(_qual_tbl) is not null then   -- table exists
      execute 'SELECT EXISTS (SELECT FROM ' || _qual_tbl || ')'
      into _row_found;

      if _row_found then                        -- table is not empty
         execute 'TRUNCATE ' || _qual_tbl;
         return 'Table truncated: ' || _qual_tbl;
      else  -- optional!
         return 'Table exists but is empty: ' || _qual_tbl;
      end if;
   else  -- optional!
      return 'Table not found: ' || _qual_tbl;
   end if;
end
$func$;

--test_users table
--truncate table test_users RESTART IDENTITY;
select truncate_if_exists('test_users');

insert
	into
	test_users (name,
	email,
	password,
	is_admin,
	is_enabled )
values
('test5', 'y1363huf5@mozmail.com', 'y1363huf5', false, true),
('test4', 'tqdsijgmj@mozmail.com', 'tqdsijgmj', false, true),
('test3', 'of9p4kmb7@mozmail.com', 'of9p4kmb7', false, true),
('test2', '2zhdetnft@mozmail.com', '2zhdetnft', false, true),
('test1', 'vhsi2tt9d@mozmail.com', 'vhsi2tt9d', false, true);

--test_contexts table
--truncate table test_contexts RESTART IDENTITY;
select truncate_if_exists('test_contexts');

insert into test_contexts (name, is_root, is_enabled, tags, "order")
values
('food', false, true,null,2),
('news', false, true,null,1),
('learning', false, true,null,3),
('science', false, true, 'news',2),
('software', false, true,null,3),
('Game', false, true,null,2),
('tutorials', false, true,null,2),
('books', false, true,null,3),
('3d and graphics', false, true,null,2),
('tools', false, true,'important,for work',3),
('store', false, true,null,1);

--test_tags table
--truncate table test_tags RESTART IDENTITY;
select truncate_if_exists('test_tags');

insert into test_tags (name, is_enabled)
values 
('linux', true),
('games', true),
('tool', true),
('store', true),
('news', true),
('nasa', true),
('tutorials', true),
('recipes', true),
('for work',true),
('important',true),
('design', true);

--test_bookmarks table
--truncate table test_bookmarks RESTART IDENTITY;
select truncate_if_exists('test_bookmarks');

insert into test_bookmarks (link,name,context,tags,"order")
values 
('https://pomelo-tools.vercel.app/design/palette','Color Palettes|Pomelo-tools','tools','tool',2),
('https://librivox.org','librivox|free public domain audiobooks','books',null,null),
('https://openlibrary.org','Open Library','books',null,2),
('https://ambientcg.com','ambientCG - Free Textures, HDRIs and Models','3d and graphics','design',2),
('https://www.blendernation.com','BlenderNation &#x2d; Daily Blender Art, Tutorials, Development and Community News','3d an graphics','design',null),
('https://physicallybased.info','physicallybased.info','3d and graphics',null,2),
('https://topologyguides.com','Topology Guides','3d and graphics',null,null),
('https://homerepairtutor.com','Home Repair Tutor - Make Home Renovation Easier','tutorials','tutorials',2),
('https://www.instructables.com','Yours for the making - Instructables','tutorials',null,null),
('https://www.ifixit.com','iFixit: The Free Repair Manual','tutorials','tutorials',2),
('https://computingforgeeks.com','computingforgeeks.com','tutorials',null,2),
('https://software.nasa.gov','Home | NASA Software Catalog','software','nasa',1),
('https://www.gimp.org/tutorials/','GIMP - Tutorials','software','tutorials',2),
('https://docs.krita.org/en/','Welcome to the Krita 5.2 Manual! Krita Manual 5.2.0 documentation','software','tutorials,design',2),
('https://inkscape.org/learn/tutorials/','Inkscape Tutorials | Inkscape','software','tutorials,design',2),
('https://www.edx.org','MIT OpenCourseWare | Free Online Course Materials','learning',null,null),
('https://ocw.mit.edu','Khan Academy','learning',null,2),
('https://www.khanacademy.org','Learn to Code - for Free | Codecademy','learning',null,2),
('https://www.codecademy.com','The home of free learning from the Open University | OpenLearn - Open University','learning',null,2),
('https://www.open.edu/openlearn/','www.coursera.org','learning',null,2),
('https://www.coursera.org','Home | Learning for a Lifetime | Stanford Online','learning',null,2),
('https://online.stanford.edu','edX | Online Courses, Certificates &amp; Degrees from Leading Institutions','learning',null,2),
('https://www.polygon.com','Polygon: Gaming and Entertainment News, Culture, Reviews, and More','Game',null,2),
('https://kotaku.com/latest','Latest - Gaming Reviews, News, Tips and More. | Kotaku','Game','news,games',2),
('https://www.gamespot.com','Video Games Reviews & News - GameSpot','Game','news,games',2),
('https://gamefaqs.gamespot.com/?validate=1','gamefaqs.gamespot.com','Game','games',2),
('https://www.repairclinic.com','www.repairclinic.com','store',null,2),
('https://www.ebay.com','www.ebay.com','store','store',2),
('https://www.amazon.com','Amazon.com','store','store',2),
('https://aliexpress.ru','AliEхpress | Интернет-магазин | Официальный сайт','store','store',2),
('https://itch.io','Download the latest indie games - itch.io','store','store,games',2),
('https://store.steampowered.com','Welcome to Steam','store','store,games',2),
('https://store.epicgames.com/','store.epicgames.com','store','store,games',2),
('https://www.gog.com/','GOG.com','store','store,games',2),
('https://www.nasa.gov/2025-news-releases/','2025 News Releases - NASA','science','news,nasa',1),
('https://www.nationalgeographic.com','www.nationalgeographic.com','science','news',1),
('https://www.popsci.com/tag/news/','News | Popular Science','science',null,null),
('https://www.technologyreview.com','www.technologyreview.com','science',null,2),
('https://www.allrecipes.com/recipes/95/pasta-and-noodles/','Pasta and Noodle Recipes','food','recipes',2),
('https://www.bbcgoodfood.com/health/nutrition','Nutrition | Good Food','food',null,null),
('https://world.openfoodfacts.org','world.openfoodfacts.org','food',null,2),
('https://fdc.nal.usda.gov/food-search?type=Branded','Food Search | USDA FoodData Central','food',null,null),
('https://www.wired.com','WIRED - The Latest in Technology, Science, Culture and Business | WIRED','news',null,2),
('https://www.nature.com/news','Latest science news, discoveries and analysis','news',null,null),
('https://gizmodo.com','Gizmodo | The Future Is Here','news','news',1),
('https://www.healthline.com','Healthline: Medical information and health advice you can trust.','news',null,2),
('https://www.tomshardware.com','Toms Hardware: For The Hardcore PC Enthusiast','news',null,2),
('https://www.cnet.com','CNET: Product reviews, advice, how-tos and the latest news','news',null,null),
('https://www.techradar.com','TechRadar | the technology experts','news',null,2),
('https://www.pcworld.com','PCWorld','news',null,2),
('https://www.debian.org/index.en.html','Debian -- The Universal Operating System','',null,2),
('https://wiki.manjaro.org/index.php/Fstab/ru','Fstab - Manjaro','','linux,tutorials',2),
('https://www.reddit.com/','Reddit - The heart of the internet','','important',1),
('https://docs.helix-editor.com/','Helix','',null,1),
('https://www.youtube.com/watch?v=dQw4w9WgXcQ','Rick Astley - Never Gonna Give You Up (Official Video) (4K Remaster) - YouTube','','important,for work',1),
('https://studio.blender.org/training/?utm_medium=www-footer','Training - Blender Studio','','tutorial',1),
('https://relay.firefox.com/accounts/profile/','relay.firefox.com','',null,2),
('https://regex101.com','regex101: build, test, and debug regex','','tool',2),
('https://www.postgresql.org/docs/17/backup.html','PostgreSQL: Documentation: 17: Chapter 25. Backup and Restore','',null,null),
('https://www.imdb.com/calendar/?region=US&type=MOVIE&ref_=rlm','Upcoming releases - IMDb','',null,2),
('https://www.bbc.com/weather/map','BBC Weather','',null,2),
('https://penpot.app/design','penpot.app','','design,tool',2),
('https://www.gnu.org/software/sed/manual/sed.html','sed, a stream editor','','linux',2),
('https://firacode.org','FiraCode Font – Elevate Your Coding with Stylish Ligatures','',null,null),
('https://marketplace.visualstudio.com/search?target=VSCode&category=All%20categories&sortBy=Installs','Visual Studio Marketplace','',null,2),
('https://vercel.com/guides','Guides','','tutorial',2),
('https://archlinux.org/packages/','Arch Linux - Package Search','','linux',2),
('https://www.tiktok.com/channel/funny-cats', 'Funny cats', '','important, for work',1),
('https://historyarchive.org','History Archive | Public Domain Books, Maps, Artwork and More','',null,2);


