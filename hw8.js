(function(){
// define angular module/app
var formApp = angular.module('formApp', []);
 // create angular controller and pass in $scope and $http
formApp.controller("formController",['$scope','$http','$sce', '$window', function($scope,$http,$sce,$window) {
  $scope.tab=1;
  $scope.activeTab=1;
  $scope.activeAlbum=0;
  $scope.detailsActive=false;
  $scope.detailsNoAlbums=true;
  $scope.detailsNoPosts=true;
  $scope.process=false;
  $scope.processDetails=false;
  var url = "hw8.php"; // the script where you handle the form input.
  $scope.json_file;
  $scope.json_localStore=[];
  for ( var i = 0, len = localStorage.length; i < len; ++i ) {
    $scope.json_localStore.push(JSON.parse($window.localStorage.getItem( localStorage.key( i ) )));
  }

  navigator.geolocation.getCurrentPosition(showPosition);

  function showPosition(position) {
    $scope.latitude = position.coords.latitude;
    $scope.longitude = position.coords.longitude;
    console.log($scope.latitude + $scope.longitude);
  }
  if ($scope.latitude === 0) {
    $scope.latitude = 34.019481;
    $scope.longitude = -118.289549;
  }
  

  $scope.processForm = function(keyword) {
    $("#resultData").hide();
    //console.log(crd.latitude);
    $scope.process=true;
    $.ajax({

      type: "GET",
      url: url,
      data: {keyword: keyword, lat:$scope.latitude, long:$scope.longitude},
      success: function(msg) {
        $scope.process=false;
        //console.log(msg);
        $scope.json_file = $.parseJSON(msg);
        //console.log($scope.json_file);
        

        //user pagination button
        if ($scope.json_file.user.data.length === 25) {
          $scope.usernextActive = true;
        } else {
          $scope.usernextActive = false;
        }
        if ($scope.json_file.user.paging.previous) {
          $scope.userpreActive = true;
        } else {
          $scope.userpreActive = false;
        }

        // page pagination button
        if ($scope.json_file.page.data.length === 25) {
          $scope.pagenextActive = true;
        } else {
          $scope.pagenextActive = false;
        }
        if ($scope.json_file.page.paging.previous) {
          $scope.pagepreActive = true;
        } else {
          $scope.pagepreActive = false;
        }

        // event
        if ($scope.json_file.event.data.length === 25) {
          $scope.eventnextActive = true;
        } else {
          $scope.eventnextActive = false;
        }
        if ($scope.json_file.event.paging.previous) {
          $scope.eventpreActive = true;
        } else {
          $scope.eventpreActive = false;
        }

        // place
        if ($scope.json_file.place.data.length === 25) {
          $scope.placenextActive = true;
        } else {
          $scope.placenextActive = false;
        }
        if ($scope.json_file.place.paging.previous) {
          $scope.placepreActive = true;
        } else {
          $scope.placepreActive = false;
        }

        // group
        if ($scope.json_file.group.data.length === 25) {
          $scope.groupnextActive = true;
        } else {
          $scope.groupnextActive = false;
        }
        if ($scope.json_file.place.paging.previous) {
          $scope.grouppreActive = true;
        } else {
          $scope.grouppreActive = false;
        }
        $scope.$apply();
        $("#resultData").show();
      }

      


    });
  }
  /*
  $("#searchForm").submit(function(e) {
    $("#resultData").hide();
    $scope.process=true;
    $.ajax({


      xhr: function () {
        var xhr = new window.XMLHttpRequest();

        xhr.addEventListener("progress", function (evt) {
            if (evt.lengthComputable) {
                alert("percentage");
                var percentComplete = evt.loaded / evt.total;
                //console.log(percentComplete);
                
            }
            $scope.process=false;
        }, false);
        return xhr;
    },


      type: "GET",
      url: url,
      data: {keyword: $("#searchForm").keyword}, // serializes the form's elements.
      success: function(msg) {
        //console.log(msg);
        $scope.json_file = $.parseJSON(msg);
        //console.log($scope.json_file);
        

        //user pagination button
        if ($scope.json_file.user.data.length === 25) {
          $scope.usernextActive = true;
        } else {
          $scope.usernextActive = false;
        }
        if ($scope.json_file.user.paging.previous) {
          $scope.userpreActive = true;
        } else {
          $scope.userpreActive = false;
        }

        // page pagination button
        if ($scope.json_file.page.data.length === 25) {
          $scope.pagenextActive = true;
        } else {
          $scope.pagenextActive = false;
        }
        if ($scope.json_file.page.paging.previous) {
          $scope.pagepreActive = true;
        } else {
          $scope.pagepreActive = false;
        }

        // event
        if ($scope.json_file.event.data.length === 25) {
          $scope.eventnextActive = true;
        } else {
          $scope.eventnextActive = false;
        }
        if ($scope.json_file.event.paging.previous) {
          $scope.eventpreActive = true;
        } else {
          $scope.eventpreActive = false;
        }

        // place
        if ($scope.json_file.place.data.length === 25) {
          $scope.placenextActive = true;
        } else {
          $scope.placenextActive = false;
        }
        if ($scope.json_file.place.paging.previous) {
          $scope.placepreActive = true;
        } else {
          $scope.placepreActive = false;
        }

        // group
        if ($scope.json_file.group.data.length === 25) {
          $scope.groupnextActive = true;
        } else {
          $scope.groupnextActive = false;
        }
        if ($scope.json_file.place.paging.previous) {
          $scope.grouppreActive = true;
        } else {
          $scope.grouppreActive = false;
        }
        $scope.$apply();
        $("#resultData").show();
      }

      


    });
  }); // submit function end*/
  $scope.clearAll = function() {
    $("#resultData").hide();
  }
  $scope.pagination = function(paging_data,type) {
    $("#resultData").hide();
    $scope.process=true;
    $.ajax({

      type: "GET",
      url: url,
      data: paging_data,
      success: function(msg) {
        
        var tempJson = $.parseJSON(msg);
        
        if (type === 'user') {
          $scope.json_file.user = tempJson;
          if (tempJson.data.length === 25) {
            $scope.usernextActive = true;
          } else {
            $scope.usernextActive = false;
          }
          if (tempJson.paging.previous) {
            $scope.userpreActive = true;
          } else {
            $scope.userpreActive = false;
          }
        } else if (type === 'page') {
          $scope.json_file.page = tempJson;
          if (tempJson.data.length === 25) {
            $scope.pagenextActive = true;
          } else {
            $scope.pagenextActive = false;
          }
          if (tempJson.paging.previous) {
            $scope.pagepreActive = true;
          } else {
            $scope.pagepreActive = false;
          }
        } else if (type === 'event') {
          $scope.json_file.event = tempJson;
          if (tempJson.data.length === 25) {
            $scope.eventnextActive = true;
          } else {
            $scope.eventnextActive = false;
          }
          if (tempJson.paging.previous) {
            $scope.eventpreActive = true;
          } else {
            $scope.eventpreActive = false;
          }
        } else if (type === 'place') {
          $scope.json_file.place = tempJson;
          if (tempJson.data.length === 25) {
            $scope.placenextActive = true;
          } else {
            $scope.placenextActive = false;
          }
          if (tempJson.paging.previous) {
            $scope.placepreActive = true;
          } else {
            $scope.placepreActive = false;
          }
        } else if (type === 'group') {
          $scope.json_file.group = tempJson;
          if (tempJson.data.length === 25) {
            $scope.groupnextActive = true;
          } else {
            $scope.groupnextActive = false;
          }
          if (tempJson.paging.previous) {
            $scope.grouppreActive = true;
          } else {
            $scope.grouppreActive = false;
          }
        }
        $scope.process=false;
        $scope.$apply();
        $("#resultData").show();
      }
    });
  }

  $scope.loadDetails = function(idOfItem,type,item) {
    if (type === 'event') {
      $scope.detailsNoAlbums=true;
      $scope.detailsNoPosts=true;
      $scope.details_file = 
                  {
                    "type" : 'event',
                    "id" : item.id,
                    "name" : item.name,
                    "picture" : item.picture
                  };
      return;
    }
    $scope.processDetails=true;
    $(".albumsContent").hide();
    $(".postsContent").hide();
    $(".panel-warning").hide();
    $.ajax({
      type: "GET",
      url: url,
      data: 'id='+idOfItem,
      success: function(msg) {
        $scope.details_file = $.parseJSON(msg);
        if ("albums" in $scope.details_file) {
          $scope.detailsNoAlbums=false; 
          $(".albumsContent").show();
        } else {
          $scope.detailsNoAlbums=true; 
          $(".detailsNoAlbums").show();
        }

        if ("posts" in $scope.details_file) {
          $scope.detailsNoPosts=false; 
          $(".postsContent").show();
        } else {
          $scope.detailsNoPosts=true; 
          $(".detailsNoPosts").show();
        }
        //"albums" in $scope.details_file ? ($scope.detailsNoAlbums=false; $(".albumsContent").show()) : ($scope.detailsNoAlbums=true; $(".detailsNoAlbums").show());
        //"posts" in $scope.details_file ? ($scope.detailsNoPosts=false; $(".postsContent").show()) : ($scope.detailsNoPosts=true; $(".detailsNoPosts").show());
        $scope.processDetails=false;
        $scope.$apply();
      }
    });
  }

  $scope.changeAlbumItem = function(index) {
    if ($scope.activeAlbum === index) {
      $scope.activeAlbum = -1;
    } else {
      $scope.activeAlbum = index;
    }
  }

  $scope.convertTime = function(rawTime) {
    $scope.convertedTime = moment(rawTime,"YYYY-MM-DDTHH:mm:ss.SSSZ").format('YYYY-MM-DD HH:mm:ss');
  }

  $scope.shareOnFacebook = function(name, url) {
    FB.init({
      appId      : '167633913744385',
      xfbml      : true,
      version    : 'v2.8'
    });

    FB.ui({
      method: 'feed',
      picture: url, 
      name: name, 
      caption: 'FB SEARCH FROM USC CSCI571',
    }, function(response){
      if (response && !response.error_message) {
        alert("Posted successfully");
      } else {
        alert('No posted');
      }
      });
    
  }

  $scope.localStore = function(item) {
    if ($window.localStorage.getItem(item.id) !== null) {
      $window.localStorage.removeItem(item.id);
      $scope.json_localStore = [];
      for ( var i = 0, len = localStorage.length; i < len; ++i ) {
        $scope.json_localStore.push(JSON.parse($window.localStorage.getItem( localStorage.key( i ) )));
      }
      //console.log($scope.json_localStore);
      return;
    }
    var type;
    switch ($scope.activeTab) {
      case 1:
        type = 'user';
        break;
      case 2:
        type = 'page';
        break;
      case 3:
        type = 'event';
        break;
      case 4:
        type = 'place';
        break;
      case 5:
        type = 'group';
        break;
      default:

    }
    
    var new_item = 
                  {
                    "type" : type,
                    "id" : item.id,
                    "name" : item.name,
                    "picture" : item.picture
                  };
    //console.log(new_item);
    //var new_item_json = JSON.parse(new_item);
    //console.log(new_item_json);
    //$scope.json_localStore.push(new_item);
    //console.log($scope.json_localStore);
    $window.localStorage.setItem(item.id, JSON.stringify(new_item));
    
    //console.log($window.localStorage);
    
    //if ($scope.json_localStore === 0) {
      //$scope.json_localStore = JSON.parse(JSON.stringify(new_item);
      //console.log($scope.json_localStore);
    //} else {
      //$scope.json_localStore.push(JSON.parse(JSON.stringify(new_item));
    //}
    $scope.json_localStore.push(new_item);
    //console.log($scope.json_localStore);
    //$scope.json_localStore = JSON.parse($window.localStorage);
    //console.log(JSON.parse($window.localStorage));
  }
  
  $scope.getLocalStore = function(id) {
    return localStorage.getItem(id) !== null;
  }

  $scope.addDetails = function(item) {
    $(".panel-warning").show();
    $scope.details_file = 
                  {
                    "type" : 'event',
                    "id" : item.id,
                    "name" : item.name,
                    "picture" : item.picture
                  };
  }
}]); //controller end
})();