self.addEventListener("push", (event) => {
    console.log("Push received:", event);

    let data = {};
    try {
        data = event.data.json();
    } catch {
        data = {
            title: "Notification",
            body: event.data?.text() || "You have a new message",
        };
    }

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
            icon: data.icon || "/icon.png",
        })
    );
});
