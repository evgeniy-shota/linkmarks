import { aPriority } from "./alertsPriority";
import { aType } from "./alertsTypes";

export default {
    alertList: [],
    isEnabled: true,

    addAlert(message, type = aType.default, priority = aPriority.default) {
        console.log("add new alert: " + message);

        if (priority === aPriority.veryHigh) {
            this.alertList.unshift({
                message: message,
                type: type,
                priority: priority,
            });
        } else {
            if (this.alertList.length === 0 || priority === aPriority.default) {
                this.alertList.push({
                    message: message,
                    type: type,
                    priority: priority,
                });
            } else {
                for (let i = 0; i < this.alertList.length; i++) {
                    if (this.alertList[i].priority === aPriority.default) {
                        this.alertList.splice(i, 0, {
                            message: message,
                            type: type,
                            priority: priority,
                        });
                    } else if (i == this.alertList.length - 1) {
                        this.alertList.push({
                            message: message,
                            type: type,
                            priority: priority,
                        });
                    }
                }
            }
        }
    },

    delAlert(index) {
        console.log("delete alert");
        this.alertList.splice(index, 1);
    },
};
