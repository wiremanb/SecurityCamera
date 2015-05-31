__author__ = 'Benjamin Wireman'

import BaseHTTPServer
from SimpleHTTPServer import SimpleHTTPRequestHandler

class webServer:
    protocol = 'HTTP/1.1'
    server = 0
    handler = 0
    port = 0
    serverAddress = 0
    completeServer = 0

    def init(self, addr, prt):
        try:
            self.server = BaseHTTPServer.HTTPServer
            self.handler = SimpleHTTPRequestHandler
            self.port = prt
            self.serverAddress = (addr, prt)
            self.handler.protocol_version = self.protocol
            self.completeServer = self.server(self.serverAddress, self.handler)
        except:
            print("Exception caught")

    def run(self):
        try:
            print("The server is running now...")
            self.completeServer.serve_forever()
        except:
            print("Exception caught")
