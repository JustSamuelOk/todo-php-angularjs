var app = angular.module('todo-app', []);

app.controller('todoController', function ($scope, $http) {
    let apiUrl = 'http://localhost:8000/todos';
    $scope.newTodoTitle = "";
    $scope.newTodoDescription = "";
    $scope.todos = [];
    $scope.todo = {};

    function compareById(a, b) {
        if (a.id < b.id)
            return -1;
        if (a.id > b.id)
            return 1;
        return 0;
    }

    $scope.getTodos = function () {
        $http.get(apiUrl).then(function (response) {
            $scope.todos = response.data.sort(compareById);
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

    $scope.changeTodoCompletion = function (id) {
        $http.post(apiUrl + '/' + id + '/complete').then(function () {
            $scope.getTodos();
        });
    }
});
