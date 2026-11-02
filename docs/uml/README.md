# COLORS UML Diagrams

These diagrams model the implemented COLORS web application. Each diagram is provided as editable PlantUML source and a rendered PNG.

| Diagram | PlantUML source | Rendered image | Implementation scope |
| --- | --- | --- | --- |
| Use case | [`usecase.puml`](usecase.puml) | [`usecase.png`](usecase.png) | User goals available before and after login |
| Activity | [`activity.puml`](activity.puml) | [`activity.png`](activity.png) | Login retry flow, session handling, search, add, and logout behavior |
| Class | [`class.puml`](class.puml) | [`class.png`](class.png) | Browser module, PHP endpoints, shared API functions, and MySQL entities |

## Verification against the implementation

- The use cases map to `handleLogin()`, `searchColors()`, `addColor()`, and `logOut()` in `public/js/code.js`.
- The activity diagram follows the browser requests and responses implemented in `public/js/code.js`, `api/Login.php`, `api/SearchColors.php`, and `api/AddColor.php`.
- The class diagram models the `Users` and `Colors` tables from `database/schema.sql`, including the one-to-many relationship through `Colors.UserID`.
- MySQL is shown as an internal data store, not an external use-case actor.
- The logged-in state is accurately described as a client-side cookie session. The current implementation does not create a server-side session.
- The application uses procedural JavaScript and PHP. The class diagram therefore uses `JavaScript module`, `PHP script`, and `PHP module` stereotypes to show static responsibilities without claiming that literal PHP classes exist.

## Render the diagrams

With PlantUML installed, run this command from the repository root:

```bash
plantuml -tpng docs/uml/usecase.puml docs/uml/activity.puml docs/uml/class.puml
```

The committed PNGs were rendered and syntax-checked with PlantUML 1.2026.9.

## AI usage

AI assistance was used to draft the PlantUML source and organize the diagrams. Every actor, activity, endpoint, operation, database field, and relationship was then checked against the tracked application source and schema. The final diagrams were rendered and visually inspected before publication.

