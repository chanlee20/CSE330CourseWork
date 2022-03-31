import sys, os
import re
if len(sys.argv) < 2:
    sys.exit(f"Usage: {sys.argv[0]} filename")

filename = sys.argv[1]

if not os.path.exists(filename):
    sys.exit(f"Error: File '{sys.argv[1]}' not found")

class Player:
        #constructor:
        def __init__(self, name, bats, hits):
                self.name = name
                self.bats = bats
                self.hits = hits

        def getName(self):
                return self.name

        def getBats(self):
                return self.bats

        def getHits(self):
                return self.hits

        def getRuns(self):
                return self.runs

        def updateB(self, bats):
                self.bats = self.bats + bats

        def updateH(self, hits):
                self.hits = self.hits + hits

        def getAvg(self):
                if(self.getBats() == 0):
                        return 0
                else:
                        avg = self.getHits()/self.getBats()
                        return avg

listofPlayers = []
listofNames = []
count = 0

with open(filename) as f:
        for l in f:
                pat = "(.*) batted (\d*) times with (\d*) hits and (\d*) runs"
                r1 = re.match(pat,l)
                if r1 is not None:
                        name = r1.group(1)
                        bats = int(r1.group(2))
                        hits = int (r1.group(3))
                        if name not in listofNames:
                                count = 0                           
                        else:
                                count = 1

                        if(count == 0):
                                newPlayer = Player(name, bats, hits)
                                listofNames.append(name) 
                                listofPlayers.append(newPlayer)
                        else:
                                for p in listofPlayers:
                                        if p.getName() == name:
                                                p.updateH(hits);
                                                p.updateB(bats);


final_list = sorted(listofPlayers, key = lambda v: v.getAvg(), reverse = True)

for p in final_list:
        print("%s: %.3f" %(p.getName(), p.getAvg()))


                                  
