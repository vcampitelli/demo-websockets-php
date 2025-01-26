import { useState } from "preact/hooks";
import Chat from "./Chat";
import FormLogin from "./FormLogin";

export default function App() {
    const [username, setUsername] = useState(null);

    if (username === null) {
        return (
            <FormLogin
                onSubmit={(username) => {
                    setUsername(username);
                }}
            />
        );
    }

    return <Chat username={username} />;
}
