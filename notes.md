note : i didnt put any patch nor update since its my first time uploading it to gh :o.

return msg for debug and catch error: 
return [
    "msg"=>"dbError",
    "error_type" => get_class($e),
    "message" => $e->getMessage(),
    "file" => $e->getFile(),
    "line" => $e->getLine()
];

Patch 2.25.21.01 :
- Updated dbCon.php, removed autoincrement syntax on sqlite query
- Changes action in Case 'Test' ( itemService )
- Added removeItem() in itemsService
- fixed few code in itemServ

Patch 2.25.23.15 : 
- Note to self change to decoupled desig
- Created repository folder for handling interactions and database 
- Created Model for entity modelling and data consistency

Patch 2.27.14.30 : 
- Completed CRUD for Item module
- Input normalization
- Completed All error Handling on all C R U D 
- Implemented Upsert Logic

Small Patch 2.27.15.13 : 
- Starter progres on Bin module
- Added binService and binRepository
- Ran a test, returns '200 OK'

Small Patch 2.27.19.25 : 
- Added masterCont module for accessing db directly will be removed before going public 

Smoll Patch 2.27.22.13 : 
- Tweaked binServ and itemServ.php
- Created the sturc for func addBin in binServ

Patch 2.28.14.50 : 
- Finished getBin functionality
- Started on save functionality

Patch 2.28.19.40 : 
- added fun in binEntity to obj att for DB 
- started and finished making the save module in Bin Repo
- 2 out of 4 module in Bin
- as always added safety nets(trycatch) and ifelse to catch an error 

Smoll Patch 3.1.17.30 : 
- Found a bug on binRepo code specifically fetch functiion
Where $binFetched wont create new objects. Need further investigation

Smoll Patch 3.1.17.38 :
- Fixed bug on binRepo turns out foreach used wrong variables.
Should've used $result but instead used $binFetched.

Patch 3.1.20.40 : 
- Finished creating all CRUD module for BIN.
- Preping creating CRUD module for Stock