__author__ = 'Benjamin Wireman'

import WebServer

def main():
    server = WebServer.webServer()
    server.init('0.0.0.0', 8080)
    server.run()

if __name__ == '__main__':
    main()