# blogpuro-justfun

This code was written in 2015 only for study purpose, this code is NOT FOR PRODUCTION.
Unfortunately the code is not provided with test!

-Blogpuro

Simple blog written on top of Justfun framework(included with blog).

- Justfun(framework)

Php 5.x MVC Framework written for study purpose.

This framework provide some builtin features such:

- MVC(S) layer
 - Model -> Repository, Entities + Mysql Adapter full implemented
 - Controller -> Action that manage Request and return the right Response based on routing
              - Response -> HTML, JSON support, partial rendering, manage nav, inject css and more..
 - View
 - Service -> Provide some required extra business logic such Paginator

- Routing -> simple routing /controller/view
- Plugin AOP layer (for some framework's hook) -> some plugin such debugger provided
- More stuff..

This framework provided some example of many pattern implementations,
such Factory, Entity/Repository, Prototipe, Adapter, Singleton pattern and more.

Also provide an AOP implementaion for plugin's layer.

I'm python/django fullstack developer and i don't think to rollback to php anymore, 
but I decided to publish the code hoping it will be useful to someone.

I hope that i will have the time for refactoring and split well the framework from blog implementation.
Sorry for the italian comments in the code. :D

Note: there are some vendors in the public folder, such bootstrap, tinymce, holder.js and jquery.
.
├── controllers
│   ├── apiController.php
│   ├── authController.php
│   ├── authorController.php
│   ├── Controller.php
│   ├── indexController.php
│   ├── postCommentsController.php
│   ├── postController.php
│   └── testController.php
├── core
│   ├── Application.php
│   ├── Core.php
│   ├── Database.php
│   ├── Factory.php
│   ├── mysqlAdapter.php
│   ├── Plugin.php
│   ├── Request.php
│   ├── Response.php
│   ├── routingAdapter.php
│   ├── Routing.php
│   └── statusInterface.php
├── entities
│   ├── postCommentEntity.php
│   ├── postEntity.php
│   └── userEntity.php
├── index.php
├── LICENSE
├── plugins
│   ├── addFavicon.php
│   └── routerDebug.php
├── public(js+css)
├── README.md
├── repositories
│   ├── Factory.php
│   ├── postCommentsRepository.php
│   ├── postsRepository.php
│   └── usersRepository.php
├── services
│   ├── authService.php
│   ├── Factory.php
│   ├── paginatorService.php
│   ├── urlifyService.php
│   └── urlManagerService.php
├── traits
│   ├── dataPersistenceTrait.php
│   └── sessionsTrait.php
└── views
    ├── auth
    │   └── login.phtml
    ├── author
    │   ├── index.phtml
    │   └── partials
    │       ├── paginator.phtml
    │       └── posts.phtml
    ├── index
    │   ├── index.phtml
    │   └── partials
    │       ├── paginator.phtml
    │       └── posts.phtml
    ├── layouts
    │   ├── author.phtml
    │   ├── index.phtml
    │   └── partials
    │       ├── author-nav.phtml
    │       ├── footer.phtml
    │       ├── header-container.phtml
    │       ├── header-nav.phtml
    │       ├── header.phtml
    │       ├── mini-dash.phtml
    │       ├── operation-status.phtml
    │       ├── payme.phtml
    │       ├── sidebar-author.phtml
    │       ├── sidebar.phtml
    │       ├── simple-tinymce-require-header.phtml
    │       ├── stylesheets.phtml
    │       └── tinymce-require-header.phtml
    ├── post
    │   ├── add.phtml
    │   ├── edit.phtml
    │   ├── partials
    │   │   └── comments.phtml
    │   └── show.phtml
    ├── post-comments
    │   ├── edit.phtml
    │   ├── list.phtml
    │   └── partials
    │       ├── add.phtml
    │       └── comments.phtml
    └── test
        └── test.phtml
