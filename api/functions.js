import mysql from 'mysql';
const connection = mysql.createConnection({
    host: 'localhost',
    port: '8889',
    user: 'root',
    password: 'root',
    database: 'ChromeCarouselPage'
});
connection.connect();

export function getDisplayList(displayId) {
    return new Promise((resolve, reject) => {
        getDisplayListExist(displayId)
            .then((exist) => {
                if (exist) {
                    connection.query('SELECT list FROM carousels WHERE id = ?', [displayId], function (error, results, fields) {
                        if (error) reject(error);
                        if (results.length > 0) {
                            resolve(results[0].list);
                        } else {
                            resolve(false);
                        }
                    });
                } else {
                    resolve(false);
                }
            })
            .catch((err) => {
                reject(err);
            })
    });
}

export function getDisplayListExist(displayId) {
    return new Promise((resolve, reject) => {
        connection.query('SELECT list FROM carousels WHERE id = ?', [displayId], function (error, results, fields) {
            if (error) reject(error);
            if (results.length > 0) {
                resolve(true);
            } else {
                resolve(false);
            }
        });
    });
}