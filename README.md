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
I decided to publish the code hoping it will be useful to someone.
I hope that i will have the time for refactoring and split well the framework from blog implementation.
Sorry for the italian comments in the code. :D

Note: there are some vendors in the public folder, such bootstrap, tinymce, holder.js and jquery.

