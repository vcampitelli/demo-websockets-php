import { render } from "preact";
import "./css/app.css";
import App from "./App";

render(
    <main className="container">
        <App />
    </main>,
    document.getElementById("app"),
);
