var app = angular.module('todo-app', []);

app.controller('todoController', function ($scope, $http) {
    let apiUrl = 'http://localhost:8000/todos';
    $scope.newTodoTitle = "";
    $scope.newTodoDescription = "";
    $scope.todos = [];

    $scope.getTodos = function () {
        $http.get(apiUrl).then(function (response) {
            $scope.todos = response.data;
        });
    }

    $scope.addTodo = function () {
        $scope.newTodo = {
            title: $scope.newTodoTitle,
            description: $scope.newTodoDescription
        };
        $http.post(apiUrl, $scope.newTodo).then(function () {
            $scope.newTodoTitle = "";
            $scope.newTodoDescription = "";
            $scope.getTodos();
        });
    }

    $scope.deleteTodo = function (id) {
        $http.post(apiUrl + '/' + id + '/delete').then(function () {

            $scope.getTodos();
        });
    }
});
